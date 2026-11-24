@extends('layouts.app')

@section('content')
    @php
        $item = $order->items->first();
        $isPaid = $order->status === 'paid';
        $isPending = $order->status === 'pending';
    @endphp

    <div class="max-w-2xl mx-auto px-4 py-10">
        <div class="card p-6 sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-wide" style="color: var(--br)">{{ __('app.checkout_title') }}</p>
            <h1 class="text-2xl font-bold mt-1">
                {{ $isPaid ? __('app.checkout_success') : ($isPending ? __('app.checkout_pending') : __('app.checkout_failed')) }}
            </h1>

            @if (session('status'))
                <p class="soft text-sm rounded-lg p-3 mt-4" role="status">{{ session('status') }}</p>
            @endif

            <dl class="mt-6 space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt class="mut">{{ __('app.checkout_order_number') }}</dt>
                    <dd class="font-medium">#{{ $order->order_number }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="mut">{{ __('app.checkout_item') }}</dt>
                    <dd class="font-medium text-right">{{ $item?->course_title }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="mut">{{ __('app.checkout_amount') }}</dt>
                    <dd class="font-medium">{{ \App\Support\Currency::format((float) $order->total, 'BDT', 'BDT') }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="mut">{{ __('app.checkout_status') }}</dt>
                    <dd><span class="soft px-2 py-0.5 rounded-full text-xs">{{ __('app.'.$order->status) }}</span></dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="mut">{{ __('app.payment_method') }}</dt>
                    <dd class="font-medium">PayStation</dd>
                </div>
                @if ($payment?->gateway_reference)
                    <div class="flex justify-between gap-4">
                        <dt class="mut">{{ __('app.transaction_id') }}</dt>
                        <dd class="font-medium break-all text-right">{{ $payment->gateway_reference }}</dd>
                    </div>
                @endif
            </dl>

            @if ($isPaid && $item?->course_id)
                <p class="soft text-sm rounded-lg p-3 mt-6">{{ __('app.checkout_course_ready') }}</p>
            @elseif ($isPaid && $item?->service_id)
                <p class="soft text-sm rounded-lg p-3 mt-6">{{ __('app.checkout_service_note') }}</p>
            @endif

            <div class="flex flex-wrap gap-3 mt-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn">{{ __('app.checkout_back_dashboard') }}</a>
                @endauth
                <a href="{{ route('home') }}" class="btn btn-o">{{ __('app.checkout_back_home') }}</a>
            </div>
        </div>
    </div>
@endsection
