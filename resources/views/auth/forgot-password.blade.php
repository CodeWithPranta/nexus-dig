@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="card p-6 sm:p-8">
            <h1 class="text-2xl font-bold">{{ __('app.forgot_title') }}</h1>
            <p class="mut text-sm mt-1">{{ __('app.forgot_subtitle') }}</p>

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

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf

                <label class="block text-sm font-medium">
                    {{ __('app.email') }}
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="mt-1">
                </label>

                <button type="submit" class="btn w-full">{{ __('app.send_reset_link') }}</button>
            </form>

            <p class="mut text-sm mt-6 text-center">
                <a href="{{ route('login') }}" class="font-semibold hover:text-[var(--br)]">{{ __('app.back_to_login') }}</a>
            </p>
        </div>
    </div>
@endsection
