@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-10">
        <a href="{{ route('home') }}#{{ $itemType === 'course' ? 'courses' : 'services' }}" class="text-sm mut hover:text-[var(--br)]">
            ← {{ __('app.checkout_back_home') }}
        </a>

        <div class="card p-6 sm:p-8 mt-4">
            <p class="text-xs font-semibold uppercase tracking-wide" style="color: var(--br)">
                {{ $itemType === 'course' ? __('app.checkout_course') : __('app.checkout_service') }}
            </p>
            <h1 class="text-2xl font-bold mt-1">{{ $title }}</h1>

            @if ($description)
                <p class="mut mt-2">{{ $description }}</p>
            @endif

            @if ($itemType === 'service')
                <p class="soft text-sm rounded-lg p-3 mt-4">{{ __('app.checkout_service_note') }}</p>
            @endif

            <div class="mt-6">
                <h2 class="font-semibold">{{ __('app.checkout_summary') }}</h2>

                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="mut">{{ __('app.subtotal') }}</dt>
                        <dd>{{ \App\Support\Currency::format($subtotal, 'BDT', 'BDT') }}</dd>
                    </div>

                    @if ($discount > 0)
                        <div class="flex justify-between gap-4">
                            <dt class="mut">{{ __('app.discount') }}</dt>
                            <dd>−{{ \App\Support\Currency::format($discount, 'BDT', 'BDT') }}</dd>
                        </div>
                    @endif

                    <div class="flex justify-between gap-4 border-t bd pt-2 font-semibold">
                        <dt>{{ __('app.total') }}</dt>
                        <dd>{{ \App\Support\Currency::format($total, 'BDT', 'BDT') }}</dd>
                    </div>
                </dl>

                <p class="mut text-xs mt-2">{{ __('app.checkout_bdt_note') }}</p>
            </div>

            @if ($errors->any())
                <div class="text-sm text-red-600 mt-5" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ $checkoutUrl }}" class="mt-6 space-y-4">
                @csrf

                <label class="block text-sm font-medium">
                    {{ __('app.checkout_phone') }}
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required autocomplete="tel" inputmode="tel" maxlength="20" class="mt-1">
                    <span class="block mut text-xs font-normal mt-1">{{ __('app.checkout_phone_hint') }}</span>
                </label>

                <div class="text-sm">
                    <span class="mut">{{ __('app.payment_method') }}:</span>
                    <span class="font-semibold">PayStation</span>
                </div>

                <button type="submit" class="btn w-full">{{ __('app.checkout_pay') }}</button>
            </form>

            <p class="mut text-xs mt-3">{{ __('app.checkout_redirect_note') }}</p>
            <p class="mut text-xs mt-1">{{ __('app.checkout_secure_note') }}</p>
        </div>
    </div>
@endsection
