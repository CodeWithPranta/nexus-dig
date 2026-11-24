<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      data-theme="light"
      class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="NexusDig">
        <meta name="theme-color" content="#f0726c">
        <meta name="msapplication-TileColor" content="#f0726c">

        <link rel="manifest" href="/manifest.json">
        <link rel="icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/favicon.ico">

        <title>NexusDig – {{ __('app.brand_tagline') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @fonts

        <script>
            window.NexusDig = {
                locale: @json(app()->getLocale()),
                currency: @json(\App\Support\Currency::current()),
                currencies: @json(config('currency.supported')),
                rates: @json(config('currency.exchange_rates')),
                i18n: @json(__('app')),
                auth: {
                    check: @json(auth()->check()),
                    name: @json(auth()->user()?->name),
                    staff: @json(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isTeacher())),
                    dashboard: @json(route('dashboard')),
                    admin: @json(url('/admin')),
                    login: @json(route('login')),
                    register: @json(route('register')),
                    logout: @json(route('logout')),
                },
                csrf: @json(csrf_token()),
                checkout: {
                    course: @json(url('/checkout/course')),
                    service: @json(url('/checkout/service')),
                },
            };
        </script>

        <script>
            if (location.hash === '#dashboard') {
                location.replace(@json(route('dashboard')));
            }

            addEventListener('hashchange', () => {
                if (location.hash === '#dashboard') {
                    location.replace(@json(route('dashboard')));
                }
            });
        </script>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */ @layer properties{@supports (((-webkit-hyphens:none)) and (not (margin-trim:inline))) or ((-moz-orient:inline) and (not (color:rgb(from red r g b)))){*,:before,:after,::backdrop{--tw-translate-x:0;--tw-translate-y:0;--tw-translate-z:0;--tw-rotate-x:initial;--tw-rotate-y:initial;--tw-rotate-z:initial;--tw-skew-x:initial;--tw-skew-y:initial;--tw-space-x-reverse:0;--tw-border-style:solid;--tw-leading:initial;--tw-font-weight:initial;--tw-tracking:initial;--tw-shadow:0 0 #0000;--tw-shadow-color:initial;--tw-shadow-alpha:100%;--tw-inset-shadow:0 0 #0000;--tw-inset-shadow-color:initial;--tw-inset-shadow-alpha:100%;--tw-ring-color:initial;--tw-ring-shadow:0 0 #0000;--tw-inset-ring-color:initial;--tw-inset-ring-shadow:0 0 #0000;--tw-ring-inset:initial;--tw-ring-offset-width:0px;--tw-ring-offset-color:#fff;--tw-ring-offset-shadow:0 0 #0000;--tw-blur:initial;--tw-brightness:initial;--tw-contrast:initial;--tw-grayscale:initial;--tw-hue-rotate:initial;--tw-invert:initial;--tw-opacity:initial;--tw-saturate:initial;--tw-sepia:initial;--tw-drop-shadow:initial;--tw-drop-shadow-color:initial;--tw-drop-shadow-size:initial;--tw-duration:initial;--tw-ease:initial;--tw-content:""}}}@layer theme{:root,:host{--font-sans:"Instrument Sans", ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";--font-serif:ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;--font-mono:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;--color-red-50:oklch(97.1% .013 17.38);--color-red-100:oklch(93.6% .032 17.717);--color-red-200:oklch(88.5% .062 18.334);--color-red-300:oklch(80.8% .114 19.571);--color-red-400:oklch(70.4% .191 22.216);--color-red-500:oklch(63.7% .237 25.331);--color-red-600:oklch(57.7% .245 27.325);--color-red-700:oklch(50.5% .213 27.518);--color-red-800:oklch(44.4% .... (line truncated)
            </style>
        @endif

        <style>
            :root {
                --bg: #fff;
                --fg: #0f172a;
                --mut: #475569;
                --card: #f8fafc;
                --bd: #e2e8f0;
                --br: #f0726c;
                --brf: #fff;
                --soft: #fde8e7;
                box-sizing: border-box;
                padding-top: env(safe-area-inset-top, 0px);
                padding-bottom: env(safe-area-inset-bottom, 0px);
            }

            @media (prefers-color-scheme: dark) {
                :root:not([data-theme="light"]) {
                    --bg: #0b1020;
                    --fg: #e5e7eb;
                    --mut: #94a3b8;
                    --card: #131a2e;
                    --bd: #25304d;
                    --br: #ff8f8a;
                    --brf: #0b1020;
                    --soft: #2b1716;
                    color-scheme: dark;
                }
            }

            :root[data-theme="dark"] {
                --bg: #0b1020;
                --fg: #e5e7eb;
                --mut: #94a3b8;
                --card: #131a2e;
                --bd: #25304d;
                --br: #ff8f8a;
                --brf: #0b1020;
                --soft: #2b1716;
                color-scheme: dark;
            }

            html {
                scroll-padding-top: env(safe-area-inset-top, 0px);
            }

            body {
                background: var(--bg);
                color: var(--fg);
                font-family: Inter, "Noto Sans Bengali", system-ui, -apple-system, "Segoe UI", sans-serif;
            }

            html[lang="bn"] body {
                font-family: "Noto Sans Bengali", Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
            }

            .mut {
                color: var(--mut);
            }

            .card {
                background: var(--card);
                border: 1px solid var(--bd);
                border-radius: 16px;
                transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            }

            a.card:hover,
            .lift:hover {
                transform: translateY(-4px);
                box-shadow: 0 16px 36px -14px rgba(240, 114, 108, 0.45);
                border-color: var(--br);
            }

            .hero {
                position: relative;
                overflow: hidden;
                background: radial-gradient(60% 55% at 85% 0%, rgba(240, 114, 108, 0.22), transparent), radial-gradient(45% 45% at 0% 35%, rgba(255, 154, 98, 0.15), transparent);
            }

            .hero-net {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                pointer-events: none;
            }

            .hero > section {
                position: relative;
                z-index: 1;
            }

            .hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background-image: linear-gradient(var(--bd) 1px, transparent 1px), linear-gradient(90deg, var(--bd) 1px, transparent 1px);
                background-size: 44px 44px;
                opacity: 0.55;
                -webkit-mask-image: linear-gradient(#000, transparent 85%);
                mask-image: linear-gradient(#000, transparent 85%);
            }

            .gt {
                background: linear-gradient(90deg, var(--br), #ff9f43);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }

            .ico {
                width: 46px;
                height: 46px;
                border-radius: 12px;
                display: grid;
                place-items: center;
                font-size: 1.3rem;
                background: linear-gradient(135deg, #f0726c, #d94f4a);
                box-shadow: 0 8px 18px -8px rgba(240, 114, 108, 0.7);
            }

            .thumb {
                height: 130px;
                border-radius: 12px;
                display: grid;
                place-items: center;
                font-size: 2.5rem;
                background: linear-gradient(135deg, #f0726c, #e14b4b);
            }

            .t1 {
                background: linear-gradient(135deg, #f0726c, #ff9f43);
            }

            .t2 {
                background: linear-gradient(135deg, #e14b4b, #f58a6f);
            }

            .cta {
                border-radius: 22px;
                color: #fff;
                background: radial-gradient(60% 90% at 90% 0%, rgba(255, 154, 98, 0.45), transparent), linear-gradient(135deg, #3a1412, #8a2b27);
            }

            .chip {
                border: 1px solid var(--bd);
                background: var(--card);
                padding: 0.35rem 0.85rem;
                border-radius: 999px;
                font-size: 0.8rem;
            }

            .chip.active {
                background: var(--soft);
                color: var(--br);
                border-color: var(--br);
                font-weight: 700;
            }

            .code {
                font: 12.5px/1.75 ui-monospace, Menlo, monospace;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                background: linear-gradient(135deg, var(--br), #d94f4a);
                color: #fff;
                font-weight: 600;
                padding: 0.65rem 1.2rem;
                border-radius: 12px;
                box-shadow: 0 8px 20px -8px rgba(240, 114, 108, 0.6);
                transition: opacity 0.15s, transform 0.15s;
                border: none;
                cursor: pointer;
                white-space: nowrap;
            }

            .btn:active {
                transform: scale(0.98);
            }

            .btn:hover {
                opacity: 0.92;
            }

            .btn-o {
                background: transparent;
                color: var(--fg);
                border: 1px solid var(--bd);
                box-shadow: none;
            }

            .soft {
                background: var(--soft);
                color: var(--br);
            }

            .bd {
                border-color: var(--bd);
            }

            a:focus-visible,
            button:focus-visible,
            input:focus-visible,
            select:focus-visible,
            textarea:focus-visible {
                outline: 2px solid var(--br);
                outline-offset: 2px;
            }

            input,
            select,
            textarea {
                background: var(--bg);
                border: 1px solid var(--bd);
                border-radius: 10px;
                padding: 0.6rem 0.8rem;
                width: 100%;
                color: var(--fg);
            }

            summary {
                list-style: none;
            }

            summary::-webkit-details-marker {
                display: none;
            }

            .bar {
                height: 6px;
                border-radius: 9px;
                background: var(--bd);
            }

            .bar i {
                display: block;
                height: 100%;
                border-radius: 9px;
                background: var(--br);
            }

            .view {
                animation: f 0.2s ease;
            }

            @keyframes f {
                from {
                    opacity: 0;
                    transform: translateY(4px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .view {
                    animation: none;
                }
            }

            .tab {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.15rem;
                font-size: 0.7rem;
                font-weight: 600;
                color: var(--mut);
                text-decoration: none;
                padding: 0.5rem 0.25rem;
                border-radius: 10px;
                transition: color 0.2s, background 0.2s;
            }

            .tab.active {
                color: var(--br);
                background: color-mix(in srgb, var(--br) 12%, transparent);
            }

            #nav > a:not(.nav-ico),
            #mnav > a {
                padding: 0.45rem 0.8rem;
                border-radius: 999px;
                transition: background 0.18s, color 0.18s;
            }

            #nav > a:not(.nav-ico):hover,
            #mnav > a:hover,
            #nav > a.active,
            #mnav > a.active {
                background: color-mix(in srgb, var(--br) 14%, transparent);
                color: var(--br);
                font-weight: 700;
            }

            .nav-ico {
                display: inline-grid;
                place-items: center;
                width: 2.4rem;
                height: 2.4rem;
                border-radius: 999px;
                border: 1px solid var(--bd);
                color: var(--mut);
                transition: background 0.18s, color 0.18s, border-color 0.18s;
            }

            .nav-ico:hover {
                background: color-mix(in srgb, var(--br) 14%, transparent);
                border-color: var(--br);
                color: var(--br);
            }

            .social-ico {
                display: inline-grid;
                place-items: center;
                width: 2rem;
                height: 2rem;
                border-radius: 999px;
                color: var(--mut);
                transition: background 0.18s, color 0.18s;
            }

            .social-ico:hover {
                background: color-mix(in srgb, var(--br) 14%, transparent);
                color: var(--br);
            }

            .footer-pro {
                position: relative;
                --fg: #e8eef8;
                --mut: #9fb0c9;
                --bd: rgba(255, 255, 255, 0.1);
                --br: #ff9d97;
                color: var(--fg);
                background:
                    radial-gradient(55% 120% at 82% 0%, rgba(240, 114, 108, 0.2), transparent 62%),
                    radial-gradient(45% 90% at 8% 100%, rgba(56, 189, 248, 0.14), transparent 60%),
                    linear-gradient(180deg, #0b1220 0%, #0e1728 60%, #0a111e 100%);
            }

            .footer-pro::before {
                content: "";
                position: absolute;
                inset: 0 0 auto;
                height: 3px;
                background: linear-gradient(90deg, #f0726c, #ff9f43 45%, #38bdf8);
            }

            .footer-pro .mut {
                color: var(--mut);
            }

            .footer-pro a:hover {
                color: #fff;
            }

            .payment-card {
                background: rgba(255, 255, 255, 0.97);
                border: 1px solid rgba(255, 255, 255, 0.16);
                box-shadow: 0 18px 40px -20px rgba(0, 0, 0, 0.7);
            }

            .badge-ring {
                position: relative;
                display: inline-flex;
                border-radius: 999px;
                padding: 2px;
                overflow: hidden;
            }

            .badge-ring::before {
                content: "";
                position: absolute;
                inset: -50%;
                background: conic-gradient(from 0deg, transparent 0 62%, var(--br) 78%, #ffb199 88%, transparent 100%);
                animation: spin 5s linear infinite;
            }

            .badge-ring > * {
                position: relative;
                z-index: 1;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            .marquee {
                overflow: hidden;
                -webkit-mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
                mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent);
            }

            .marquee-track {
                display: flex;
                width: max-content;
                animation: marquee 30s linear infinite;
            }

            .marquee:hover .marquee-track {
                animation-play-state: paused;
            }

            @keyframes marquee {
                from {
                    transform: translateX(0);
                }
                to {
                    transform: translateX(-50%);
                }
            }

            .team-photo {
                aspect-ratio: 1;
                border-radius: 16px;
                display: grid;
                place-items: center;
                font-size: 2rem;
                font-weight: 800;
                letter-spacing: 0.02em;
                color: #fff;
                background: linear-gradient(135deg, hsl(var(--h, 8) 82% 64%), hsl(calc(var(--h, 8) + 18) 72% 46%));
                box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.18);
            }

            .team-photo-lg {
                font-size: 3.5rem;
                border-radius: 20px;
            }

            .chat-fab {
                display: grid;
                place-items: center;
                width: 3rem;
                height: 3rem;
                border-radius: 999px;
                color: #fff;
                box-shadow: 0 12px 26px -10px rgba(0, 0, 0, 0.45);
                animation: vibrate 4s ease-in-out infinite;
                transition: transform 0.2s;
            }

            .chat-fab:hover {
                transform: scale(1.06);
            }

            .chat-wa {
                background: #25d366;
            }

            .chat-ms {
                background: #0084ff;
                animation-delay: 1.1s;
            }

            @keyframes vibrate {
                0%,
                70%,
                100% {
                    transform: rotate(0) translateX(0);
                }
                72% {
                    transform: rotate(-8deg) translateX(-1px);
                }
                74% {
                    transform: rotate(8deg) translateX(1px);
                }
                76% {
                    transform: rotate(-6deg) translateX(-1px);
                }
                78% {
                    transform: rotate(6deg) translateX(1px);
                }
                80% {
                    transform: rotate(0) translateX(0);
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .marquee-track,
                .badge-ring::before,
                .chat-fab {
                    animation: none !important;
                }
            }
        </style>
    </head>
    <body class="min-h-screen flex flex-col text-antialiased selection:bg-[#fde8e7] selection:text-[#7a2724] dark:selection:bg-[#3a1a18] dark:selection:text-[#ffd8d5]">
        <header class="sticky top-0 z-40 border-b bd" style="background: color-mix(in srgb, var(--bg) 82%, transparent); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px)">
            <div class="max-w-6xl mx-auto px-4 h-17 flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="font-extrabold text-xl tracking-tight flex items-center gap-2 shrink-0">
                    <span class="ico !w-8 !h-8 !rounded-lg text-white text-sm font-bold">N</span>
                    <span class="hidden sm:inline">Nexus<span class="gt">Dig</span></span>
                </a>

                <nav id="nav" class="hidden lg:flex items-center gap-2 xl:gap-5 text-sm font-medium" aria-label="{{ __('app.main_nav') }}">
                    <a href="{{ route('home') }}#home">{{ __('app.nav_home') }}</a>
                    <a href="{{ route('home') }}#courses">{{ __('app.nav_courses') }}</a>
                    <a href="{{ route('home') }}#services">{{ __('app.nav_services') }}</a>
                    <a href="{{ route('home') }}#team">{{ __('app.nav_team') }}</a>
                    <a href="{{ route('home') }}#analyzer">{{ __('app.nav_analyzer') }}</a>
                    <a href="{{ route('home') }}#contact">{{ __('app.nav_contact') }}</a>
                </nav>

                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <div class="hidden md:flex items-center gap-0.5 mr-1">
                        <a href="https://www.facebook.com/nexusdig" target="_blank" rel="noopener" class="social-ico" aria-label="{{ __('app.social_facebook') }}" title="{{ __('app.social_facebook') }}">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/@nexusdig" target="_blank" rel="noopener" class="social-ico" aria-label="{{ __('app.social_youtube') }}" title="{{ __('app.social_youtube') }}">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22.5 6.5a3 3 0 0 0-2.1-2.1C18.6 4 12 4 12 4s-6.6 0-8.4.4A3 3 0 0 0 1.5 6.5 31 31 0 0 0 1 12a31 31 0 0 0 .5 5.5 3 3 0 0 0 2.1 2.1C5.4 20 12 20 12 20s6.6 0 8.4-.4a3 3 0 0 0 2.1-2.1A31 31 0 0 0 23 12a31 31 0 0 0-.5-5.5z"/><polygon points="10,9 16,12 10,15" fill="currentColor" stroke="none"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/company/nexusdig" target="_blank" rel="noopener" class="social-ico" aria-label="{{ __('app.social_linkedin') }}" title="{{ __('app.social_linkedin') }}">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                    </div>
                    <a href="{{ route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn') }}"
                       class="btn btn-o !px-3 text-xs"
                       aria-label="{{ __('app.language') }}"
                       title="{{ __('app.language') }}">
                        {{ app()->getLocale() === 'bn' ? 'EN' : 'বাংলা' }}
                    </a>
                    <a href="{{ route('currency.switch', \App\Support\Currency::current() === 'BDT' ? 'USD' : 'BDT') }}"
                       class="btn btn-o !px-3 text-sm font-bold"
                       aria-label="{{ __('app.currency') }}"
                       title="{{ __('app.currency') }} → {{ \App\Support\Currency::current() === 'BDT' ? 'USD' : 'BDT' }}">
                        {{ \App\Support\Currency::current() === 'BDT' ? '$' : '৳' }}
                    </a>
                    <button id="theme" class="btn btn-o !px-3" aria-label="{{ __('app.toggle_theme') }}">◐</button>
                    @auth
                        @php($panelUrl = auth()->user()->isSuperAdmin() || auth()->user()->isTeacher() ? url('/admin') : route('dashboard'))
                        <details class="relative">
                            <summary class="btn !hidden lg:!inline-flex !px-3 cursor-pointer" aria-label="{{ __('app.account') }}" title="{{ __('app.account') }}">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </summary>
                            <div class="absolute right-0 mt-2 w-52 card p-2 shadow-xl z-50" style="background: var(--bg)">
                                <p class="px-3 py-2 text-xs mut truncate">{{ __('app.signed_in_as', ['email' => auth()->user()->email]) }}</p>
                                <a href="{{ $panelUrl }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-[var(--soft)]">{{ __('app.my_dashboard') }}</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left rounded-lg px-3 py-2 text-sm hover:bg-[var(--soft)]">{{ __('app.logout') }}</button>
                                </form>
                            </div>
                        </details>
                    @else
                        <a href="{{ route('login') }}" class="btn !hidden lg:!inline-flex !px-3" aria-label="{{ __('app.login') }}" title="{{ __('app.login') }}">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </a>
                    @endauth
                    <button id="burger" class="btn btn-o lg:!hidden !px-3" aria-label="{{ __('app.open_menu') }}" aria-expanded="false">☰</button>
                </div>
            </div>

            <nav id="mnav" class="hidden lg:hidden border-t bd px-4 py-3 flex-col gap-3 text-sm font-medium" aria-label="{{ __('app.mobile_nav') }}">
                <a href="{{ route('home') }}#home">{{ __('app.nav_home') }}</a>
                <a href="{{ route('home') }}#courses">{{ __('app.nav_courses') }}</a>
                <a href="{{ route('home') }}#services">{{ __('app.nav_services') }}</a>
                <a href="{{ route('home') }}#team">{{ __('app.nav_team') }}</a>
                <a href="{{ route('home') }}#analyzer">{{ __('app.nav_analyzer') }}</a>
                <a href="{{ route('home') }}#contact">{{ __('app.nav_contact') }}</a>
                @auth
                    <a href="{{ auth()->user()->isSuperAdmin() || auth()->user()->isTeacher() ? url('/admin') : route('dashboard') }}">{{ __('app.my_dashboard') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-left">{{ __('app.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">{{ __('app.login') }}</a>
                    <a href="{{ route('register') }}">{{ __('app.register') }}</a>
                @endauth
            </nav>
        </header>

        <main id="app" class="flex-1 relative" tabindex="-1">
            @yield('content')
        </main>

        <footer class="footer-pro mt-16">
            <div class="max-w-6xl mx-auto px-4 pt-14 pb-10 grid gap-10 sm:grid-cols-3 text-sm">
                <div>
                    <p class="font-bold text-lg">Nexus<span style="color: var(--br)">Dig</span></p>
                    <p class="mut mt-3">{{ __('app.footer_tagline') }}</p>
                </div>
                <div>
                    <p class="font-semibold mb-3">{{ __('app.explore') }}</p>
                    <ul class="space-y-1.5 mut">
                        <li><a href="{{ route('home') }}#courses" class="transition-colors">{{ __('app.nav_courses') }}</a></li>
                        <li><a href="{{ route('home') }}#services" class="transition-colors">{{ __('app.nav_services') }}</a></li>
                        <li><a href="{{ route('home') }}#team" class="transition-colors">{{ __('app.nav_team') }}</a></li>
                        <li><a href="{{ route('home') }}#analyzer" class="transition-colors">{{ __('app.nav_analyzer') }}</a></li>
                        <li><a href="{{ route('home') }}#contact" class="transition-colors">{{ __('app.nav_contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold mb-3">{{ __('app.legal') }}</p>
                    <ul class="space-y-1.5 mut">
                        <li><a href="#" class="transition-colors">{{ __('app.privacy_policy') }}</a></li>
                        <li><a href="#" class="transition-colors">{{ __('app.terms_conditions') }}</a></li>
                        <li><a href="#" class="transition-colors">{{ __('app.refund_policy') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t" style="border-color: var(--bd)">
                <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col items-start sm:items-center gap-2 sm:gap-3">
                    <p class="text-[11px] font-semibold uppercase tracking-widest mut">{{ __('app.payments_accepted') }}</p>
                    <div class="payment-card rounded-lg p-2 w-full max-w-md sm:max-w-lg">
                        <img src="{{ asset('images/payment-gateway.png') }}"
                             alt="{{ __('app.payment_methods_alt') }}"
                             width="756"
                             height="205"
                             class="block w-full h-auto"
                             loading="lazy"
                             decoding="async">
                    </div>
                </div>
            </div>

            <div class="border-t" style="border-color: var(--bd)">
                <p class="max-w-6xl mx-auto px-4 py-6 pb-24 lg:pb-6 text-center text-xs mut">{{ __('app.copyright', ['year' => now()->year]) }}</p>
            </div>
        </footer>

        <nav class="fixed bottom-0 left-0 right-0 z-50 border-t bd lg:hidden" style="background: color-mix(in srgb, var(--bg) 85%, transparent); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); padding-bottom: env(safe-area-inset-bottom, 0px)">
            <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between gap-1">
                <a href="{{ route('home') }}#home" class="tab {{ request()->path() === '/' ? 'active' : '' }}" aria-label="{{ __('app.nav_home') }}">🏠<span>{{ __('app.nav_home') }}</span></a>
                <a href="{{ route('home') }}#courses" class="tab {{ str_starts_with(request()->path(), '/courses') ? 'active' : '' }}" aria-label="{{ __('app.nav_courses') }}">🎓<span>{{ __('app.nav_courses') }}</span></a>
                <a href="{{ route('home') }}#services" class="tab" aria-label="{{ __('app.nav_services') }}">🛠️<span>{{ __('app.nav_services') }}</span></a>
                @auth
                    <a href="{{ auth()->user()->isSuperAdmin() || auth()->user()->isTeacher() ? url('/admin') : route('dashboard') }}" class="tab {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-label="{{ __('app.nav_student') }}">📊<span>{{ __('app.nav_student') }}</span></a>
                @else
                    <a href="{{ route('login') }}" class="tab {{ request()->routeIs('login') ? 'active' : '' }}" aria-label="{{ __('app.login') }}">📊<span>{{ __('app.login') }}</span></a>
                @endauth
                <a href="{{ route('home') }}#contact" class="tab" aria-label="{{ __('app.nav_contact') }}">✉️<span>{{ __('app.nav_contact') }}</span></a>
            </div>
        </nav>

        <div id="svc-modal" class="fixed inset-0 z-[70] hidden" role="dialog" aria-modal="true" aria-labelledby="svc-modal-title">
            <div class="absolute inset-0 bg-black/50" data-close-svc></div>
            <div class="absolute inset-x-4 bottom-4 sm:inset-0 sm:m-auto sm:h-fit sm:max-w-lg card p-6 shadow-2xl max-h-[85vh] overflow-y-auto" style="background: var(--bg)">
                <button type="button" class="nav-ico absolute right-3 top-3" data-close-svc aria-label="{{ __('app.close') }}">✕</button>
                <div id="svc-modal-body"></div>
            </div>
        </div>

        <div class="fixed right-4 z-50 flex flex-col gap-3 bottom-[calc(4.5rem+env(safe-area-inset-bottom,0px))] lg:bottom-6">
            <a href="https://wa.me/8801700000000" target="_blank" rel="noopener" class="chat-fab chat-wa" aria-label="{{ __('app.chat_whatsapp') }}" title="{{ __('app.chat_whatsapp') }}">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2zm0 18.15c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 4.54 0 8.24 3.7 8.24 8.24 0 4.55-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.38.11-.5.11-.11.25-.29.37-.44.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.44-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1s.9 2.44 1.03 2.61c.12.17 1.78 2.72 4.31 3.81.6.26 1.07.42 1.44.54.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.14-1.18-.06-.11-.22-.17-.47-.29z"/></svg>
            </a>
            <a href="https://m.me/nexusdig" target="_blank" rel="noopener" class="chat-fab chat-ms" aria-label="{{ __('app.chat_messenger') }}" title="{{ __('app.chat_messenger') }}">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor" aria-hidden="true"><path d="M12 2C6.36 2 2 6.13 2 11.7c0 2.86 1.13 5.36 2.97 7.07.16.14.25.34.27.55l.1 1.77c.03.56.6.93 1.11.7l1.98-.87c.16-.07.34-.08.5-.03 1.02.28 2.1.43 3.2.43 5.64 0 10-4.13 10-9.7S17.64 2 12 2zm5.8 7.28-2.94 4.66c-.47.75-1.47.94-2.18.42l-2.34-1.75a.6.6 0 0 0-.72 0l-3.16 2.4c-.42.32-.97-.18-.69-.63l2.94-4.66c.47-.75 1.47-.94 2.18-.42l2.34 1.75c.21.16.51.16.72 0l3.16-2.4c.42-.32.97.18.69.63z"/></svg>
            </a>
        </div>

        <div id="toast" role="status" aria-live="polite" class="fixed bottom-[90px] left-1/2 -translate-x-1/2 z-[60] card px-4 py-2 text-sm shadow-lg hidden"></div>

        @stack('scripts')

        <script>
            if ('serviceWorker' in navigator) {
                const hadServiceWorker = Boolean(navigator.serviceWorker.controller);

                navigator.serviceWorker.addEventListener('controllerchange', () => {
                    if (hadServiceWorker) {
                        window.location.reload();
                    }
                });

                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').then(registration => {
                        console.log('SW registered: ', registration.scope);
                    }).catch(error => {
                        console.log('SW registration failed: ', error);
                    });
                });
            }

            const $ = (s, r = document) => r.querySelector(s);
        </script>
    </body>
</html>
