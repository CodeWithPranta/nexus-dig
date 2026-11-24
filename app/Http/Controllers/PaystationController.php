<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaystationPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaystationController extends Controller
{
    public function callback(Request $request, PaystationPaymentService $payments): RedirectResponse
    {
        $payment = Payment::query()
            ->with('order')
            ->where('provider', 'paystation')
            ->where('transaction_ref', (string) $request->query('invoice_number', ''))
            ->first();

        if (! $payment || ! $payment->order) {
            return redirect()->route('home');
        }

        if (! $request->user() || $request->user()->id !== $payment->order->user_id) {
            return redirect()->route('login');
        }

        $status = strtolower((string) $request->query('status', ''));
        $trxId = $request->filled('trx_id') ? (string) $request->query('trx_id') : null;

        if (in_array($status, ['canceled', 'cancelled'], true)) {
            $payments->fail($payment, 'cancelled');

            return redirect()->route('checkout.result', $payment->order)->with('status', __('app.checkout_failed'));
        }

        if ($status === 'failed') {
            $payments->fail($payment, 'failed');

            return redirect()->route('checkout.result', $payment->order)->with('status', __('app.checkout_failed'));
        }

        if (! in_array($status, ['success', 'successful'], true)) {
            return redirect()->route('checkout.result', $payment->order)->with('status', __('app.checkout_pending'));
        }

        $verification = $payments->verify($payment, $trxId);

        if ($verification['status'] === 'paid') {
            $payments->complete($payment, $verification['data']);

            return redirect()->route('checkout.result', $payment->order)->with('status', __('app.checkout_success'));
        }

        if (in_array($verification['status'], ['failed', 'refunded'], true)) {
            $payments->fail($payment, $verification['status'], $verification['data']);

            return redirect()->route('checkout.result', $payment->order)->with('status', __('app.checkout_failed'));
        }

        if ($verification['status'] === 'invalid') {
            return redirect()->route('checkout.result', $payment->order)->with('status', __('app.checkout_verification_failed'));
        }

        return redirect()->route('checkout.result', $payment->order)->with('status', __('app.checkout_pending'));
    }

    public function ipn(Request $request, PaystationPaymentService $payments): JsonResponse
    {
        $payment = Payment::query()
            ->with('order')
            ->where('provider', 'paystation')
            ->where('transaction_ref', (string) $request->input('invoice_number', ''))
            ->first();

        if (! $payment || ! $payment->order) {
            return response()->json(['message' => 'Unknown invoice.'], 404);
        }

        $status = strtolower((string) $request->input('trx_status', ''));
        $trxId = $request->filled('trx_id') ? (string) $request->input('trx_id') : null;

        if (in_array($status, ['failed', 'canceled', 'cancelled'], true)) {
            $payments->fail($payment, $status === 'failed' ? 'failed' : 'cancelled');

            return response()->json(['message' => 'Payment marked failed.']);
        }

        if ($status === 'refund') {
            $payments->fail($payment, 'refunded');

            return response()->json(['message' => 'Payment marked refunded.']);
        }

        if (! in_array($status, ['success', 'successful'], true)) {
            return response()->json(['message' => 'Payment still pending.'], 202);
        }

        $verification = $payments->verify($payment, $trxId);

        if ($verification['status'] === 'paid') {
            $payments->complete($payment, $verification['data']);

            return response()->json(['message' => 'Payment verified.']);
        }

        if ($verification['status'] === 'invalid') {
            return response()->json(['message' => 'Payment verification failed.'], 409);
        }

        if (in_array($verification['status'], ['failed', 'refunded'], true)) {
            $payments->fail($payment, $verification['status'], $verification['data']);

            return response()->json(['message' => 'Payment marked failed.']);
        }

        return response()->json(['message' => 'Payment still pending.'], 202);
    }
}
