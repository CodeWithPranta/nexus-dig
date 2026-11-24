@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="card p-6 sm:p-8">
            <h1 class="text-2xl font-bold">{{ __('app.register_title') }}</h1>
            <p class="mut text-sm mt-1">{{ __('app.register_subtitle') }}</p>

            @if ($errors->any())
                <div class="text-sm text-red-600 mt-4" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <label class="block text-sm font-medium">
                    {{ __('app.name') }}
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1">
                </label>

                <label class="block text-sm font-medium">
                    {{ __('app.email') }}
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="mt-1">
                </label>

                <div>
                    <label for="password" class="block text-sm font-medium">{{ __('app.password') }}</label>
                    <div class="relative mt-1">
                        <input id="password" type="password" name="password" required autocomplete="new-password" class="pr-16">
                        <button type="button" data-toggle-password="password" aria-controls="password" aria-pressed="false" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-semibold mut hover:text-[var(--br)]">
                            {{ __('app.show_password') }}
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium">{{ __('app.confirm_password') }}</label>
                    <div class="relative mt-1">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="pr-16">
                        <button type="button" data-toggle-password="password_confirmation" aria-controls="password_confirmation" aria-pressed="false" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-semibold mut hover:text-[var(--br)]">
                            {{ __('app.show_password') }}
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn w-full">{{ __('app.create_account') }}</button>
            </form>

            <p class="mut text-sm mt-6 text-center">
                {{ __('app.have_account') }}
                <a href="{{ route('login') }}" class="font-semibold hover:text-[var(--br)]">{{ __('app.login') }}</a>
            </p>
        </div>
    </div>
@endsection
