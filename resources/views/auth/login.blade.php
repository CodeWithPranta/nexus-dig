@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="card p-6 sm:p-8">
            <h1 class="text-2xl font-bold">{{ __('app.login_title') }}</h1>
            <p class="mut text-sm mt-1">{{ __('app.login_subtitle') }}</p>

            @if (session('status'))
                <p class="soft text-sm rounded-lg p-3 mt-4" role="status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <div class="text-sm text-red-600 mt-4" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <label class="block text-sm font-medium">
                    {{ __('app.email') }}
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="mt-1">
                </label>

                <div>
                    <label for="password" class="block text-sm font-medium">{{ __('app.password') }}</label>
                    <div class="relative mt-1">
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="pr-16">
                        <button type="button" data-toggle-password="password" aria-controls="password" aria-pressed="false" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-semibold mut hover:text-[var(--br)]">
                            {{ __('app.show_password') }}
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
                    <label class="flex items-center gap-2 mut">
                        <input type="checkbox" name="remember" value="1" class="!w-auto" @checked(old('remember'))>
                        {{ __('app.remember_me') }}
                    </label>
                    <a href="{{ route('password.request') }}" class="mut hover:text-[var(--br)]">{{ __('app.forgot_password') }}</a>
                </div>

                <button type="submit" class="btn w-full">{{ __('app.login') }}</button>
            </form>

            <p class="mut text-sm mt-6 text-center">
                {{ __('app.no_account') }}
                <a href="{{ route('register') }}" class="font-semibold hover:text-[var(--br)]">{{ __('app.create_account') }}</a>
            </p>
        </div>
    </div>
@endsection
