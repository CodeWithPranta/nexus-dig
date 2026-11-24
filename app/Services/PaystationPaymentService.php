<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaystationPaymentService
{
    public function initiate(Order $order, Payment $payment, string $phone): string
    {
        if (blank(config('services.paystation.merchant_id')) || blank(config('services.paystation.password'))) {
            throw new RuntimeException(__('app.checkout_verification_failed'));
        }

        $payload = [
            'merchantId' => (string) config('services.paystation.merchant_id'),
            'password' => (string) config('services.paystation.password'),
            'invoice_number' => (string) $payment->transaction_ref,
            'currency' => (string) config('services.paystation.currency', 'BDT'),
            'payment_amount' => (int) round((float) $payment->amount),
            'pay_with_charge' => 0,
            'reference' => $order->order_number,
            'cust_name' => $order->user->name,
            'cust_phone' => $phone,
            'cust_email' => $order->user->email,
            'callback_url' => route('payments.paystation.callback'),
            'checkout_items' => $order->items
                ->map(fn ($item): array => [
                    'name' => $item->course_title,
                    'type' => $item->item_type,
                    'amount' => (float) $item->total,
                ])
                ->values()
                ->toJson(),
        ];

        try {
            $response = Http::asForm()
                ->acceptJson()
                ->connectTimeout(5)
                ->timeout(20)
                ->post($this->baseUrl().'/initiate-payment', $payload);
        } catch (ConnectionException $exception) {
            throw new RuntimeException(__('app.checkout_verification_failed'), 0, $exception);
        }

        $data = $response->json();

        if (! is_array($data) || (string) ($data['status_code'] ?? '') !== '200' || blank($data['payment_url'] ?? null)) {
            $payment->update([
                'status' => 'failed',
                'payload' => array_merge($payment->payload ?? [], [
                    'initiate_response' => $this->safeResponse($data ?? []),
                ]),
            ]);

            throw new RuntimeException((string) ($data['message'] ?? __('app.checkout_verification_failed')));
        }

        $payment->update([
            'payload' => array_merge($payment->payload ?? [], [
                'payment_url' => (string) $data['payment_url'],
                'initiate_response' => $this->safeResponse($data),
            ]),
        ]);

        return (string) $data['payment_url'];
    }

    /**
     * @return array{status: string, data: array<string, mixed>, message?: string}
     */
    public function verify(Payment $payment, ?string $trxId = null): array
    {
        if ($payment->status === 'succeeded') {
            return [
                'status' => 'paid',
                'data' => (array) ($payment->payload['verification'] ?? []),
            ];
        }

        $response = $this->fetchStatus($payment, $trxId);
        $transaction = $response['data'] ?? null;

        if ((string) ($response['status_code'] ?? '') !== '200' || ! is_array($transaction)) {
            return [
                'status' => 'pending',
                'data' => [],
                'message' => (string) ($response['message'] ?? ''),
            ];
        }

        if ((string) ($transaction['invoice_number'] ?? '') !== (string) $payment->transaction_ref) {
            return [
                'status' => 'invalid',
                'data' => $transaction,
                'message' => 'Invoice mismatch.',
            ];
        }

        $amount = $transaction['request_amount']
            ?? $transaction['payment_amount']
            ?? $transaction['trx_amount']
            ?? null;

        if ($amount === null || (int) round((float) $amount) !== (int) round((float) $payment->amount)) {
            return [
                'status' => 'invalid',
                'data' => $transaction,
                'message' => 'Amount mismatch.',
            ];
        }

        if (filled($trxId) && (string) ($transaction['trx_id'] ?? '') !== (string) $trxId) {
            return [
                'status' => 'invalid',
                'data' => $transaction,
                'message' => 'Transaction mismatch.',
            ];
        }

        return match (strtolower((string) ($transaction['trx_status'] ?? ''))) {
            'success', 'successful' => ['status' => 'paid', 'data' => $transaction],
            'failed' => ['status' => 'failed', 'data' => $transaction],
            'refund' => ['status' => 'refunded', 'data' => $transaction],
            default => ['status' => 'pending', 'data' => $transaction],
        };
    }

    /**
     * @param  array<string, mixed>  $transaction
     */
    public function complete(Payment $payment, array $transaction): void
    {
        DB::transaction(function () use ($payment, $transaction): void {
            $payment->refresh();

            if ($payment->status === 'succeeded') {
                return;
            }

            $payment->update([
                'status' => 'succeeded',
                'gateway_reference' => $transaction['trx_id'] ?? null,
                'paid_at' => now(),
                'payload' => array_merge($payment->payload ?? [], [
                    'verification' => $transaction,
                    'payment_method' => $transaction['payment_method'] ?? null,
                ]),
            ]);

            $order = Order::query()
                ->whereKey($payment->order_id)
                ->lockForUpdate()
                ->first();

            if (! $order || $order->status === 'paid') {
                return;
            }

            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            foreach ($order->items as $item) {
                if (! $item->course_id) {
                    continue;
                }

                $enrollment = Enrollment::firstOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'course_id' => $item->course_id,
                    ],
                    [
                        'order_id' => $order->id,
                        'status' => 'active',
                        'lifetime_access' => true,
                        'enrolled_at' => now(),
                    ],
                );

                if ($enrollment->wasRecentlyCreated) {
                    Course::query()->whereKey($item->course_id)->increment('enrollment_count');
                }
            }
        });
    }

    /**
     * @param  array<string, mixed>  $transaction
     */
    public function fail(Payment $payment, string $status, array $transaction = []): void
    {
        if ($payment->status === 'succeeded') {
            return;
        }

        if (! in_array($status, ['failed', 'refunded', 'cancelled'], true)) {
            $payment->update([
                'payload' => array_merge($payment->payload ?? [], [
                    'verification' => $transaction,
                ]),
            ]);

            return;
        }

        $payment->update([
            'status' => $status === 'refunded' ? 'refunded' : 'failed',
            'payload' => array_merge($payment->payload ?? [], [
                'verification' => $transaction,
            ]),
        ]);

        Order::query()
            ->whereKey($payment->order_id)
            ->where('status', 'pending')
            ->update([
                'status' => match ($status) {
                    'refunded' => 'refunded',
                    'cancelled' => 'cancelled',
                    default => 'failed',
                },
                'paid_at' => null,
            ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchStatus(Payment $payment, ?string $trxId): array
    {
        $client = Http::withHeaders([
            'merchantId' => (string) config('services.paystation.merchant_id'),
        ])->acceptJson()->connectTimeout(5)->timeout(20);

        $response = filled($trxId)
            ? $client->asJson()->post($this->baseUrl().'/v2/transaction-status', ['trxId' => $trxId])
            : $client->asForm()->post($this->baseUrl().'/transaction-status', [
                'invoice_number' => (string) $payment->transaction_ref,
            ]);

        return (array) ($response->json() ?? []);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function safeResponse(array $data): array
    {
        unset($data['password']);

        return $data;
    }

    private function baseUrl(): string
    {
        $environment = strtolower((string) config('services.paystation.environment', 'sandbox'));

        return rtrim((string) config(
            $environment === 'live'
                ? 'services.paystation.live_url'
                : 'services.paystation.sandbox_url',
        ), '/');
    }
}
