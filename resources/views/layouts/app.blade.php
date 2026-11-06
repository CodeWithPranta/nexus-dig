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
        <meta name="theme-color" content="#059669">
        <meta name="msapplication-TileColor" content="#059669">

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
            };
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
                --br: #059669;
                --brf: #fff;
                --soft: #d1fae5;
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
                    --br: #34d399;
                    --brf: #0b1020;
                    --soft: #10261f;
                    color-scheme: dark;
                }
            }

            :root[data-theme="dark"] {
                --bg: #0b1020;
                --fg: #e5e7eb;
                --mut: #94a3b8;
                --card: #131a2e;
                --bd: #25304d;
                --br: #34d399;
                --brf: #0b1020;
                --soft: #10261f;
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
                box-shadow: 0 16px 36px -14px rgba(16, 185, 129, 0.45);
                border-color: var(--br);
            }

            .hero {
                position: relative;
                overflow: hidden;
                background: radial-gradient(60% 55% at 85% 0%, rgba(16, 185, 129, 0.22), transparent), radial-gradient(45% 45% at 0% 35%, rgba(20, 184, 166, 0.15), transparent);
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
                background: linear-gradient(90deg, var(--br), #06b6d4);
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
                background: linear-gradient(135deg, #059669, #0e7490);
                box-shadow: 0 8px 18px -8px rgba(16, 185, 129, 0.7);
            }

            .thumb {
                height: 130px;
                border-radius: 12px;
                display: grid;
                place-items: center;
                font-size: 2.5rem;
                background: linear-gradient(135deg, #10b981, #0e7490);
            }

            .t1 {
                background: linear-gradient(135deg, #059669, #65a30d);
            }

            .t2 {
                background: linear-gradient(135deg, #14b8a6, #2563eb);
            }

            .cta {
                border-radius: 22px;
                color: #fff;
                background: radial-gradient(60% 90% at 90% 0%, rgba(52, 211, 153, 0.45), transparent), linear-gradient(135deg, #052e2b, #065f46);
            }

            .chip {
                border: 1px solid var(--bd);
                background: var(--card);
                padding: 0.35rem 0.85rem;
                border-radius: 999px;
                font-size: 0.8rem;
            }

            .code {
                font: 12.5px/1.75 ui-monospace, Menlo, monospace;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                background: linear-gradient(135deg, #059669, #047857);
                color: #fff;
                font-weight: 600;
                padding: 0.65rem 1.2rem;
                border-radius: 12px;
                box-shadow: 0 8px 20px -8px rgba(5, 150, 105, 0.6);
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
        </style>
    </head>
    <body class="min-h-screen flex flex-col text-antialiased selection:bg-emerald-100 selection:text-emerald-900 dark:selection:bg-emerald-900 dark:selection:text-emerald-100">
        <header class="sticky top-0 z-40 border-b bd" style="background: color-mix(in srgb, var(--bg) 82%, transparent); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px)">
            <div class="max-w-6xl mx-auto px-4 h-17 flex items-center justify-between gap-4">
                <a href="#home" class="font-extrabold text-xl tracking-tight flex items-center gap-2 shrink-0">
                    <span class="ico !w-8 !h-8 !rounded-lg text-white text-sm font-bold">N</span>
                    <span class="hidden sm:inline">Nexus<span class="gt">Dig</span></span>
                </a>

                <nav id="nav" class="hidden lg:flex items-center gap-6 text-sm font-medium" aria-label="Main"></nav>

                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <a href="{{ route('locale.switch', app()->getLocale() === 'bn' ? 'en' : 'bn') }}"
                       class="btn btn-o !px-3 text-xs"
                       aria-label="{{ __('app.language') }}"
                       title="{{ __('app.language') }}">
                        {{ app()->getLocale() === 'bn' ? 'EN' : 'বাংলা' }}
                    </a>
                    <a href="{{ route('currency.switch', \App\Support\Currency::current() === 'BDT' ? 'USD' : 'BDT') }}"
                       class="btn btn-o !px-3 text-xs"
                       aria-label="{{ __('app.currency') }}"
                       title="{{ __('app.currency') }}">
                        {{ \App\Support\Currency::current() === 'BDT' ? '$ USD' : '৳ BDT' }}
                    </a>
                    <button id="theme" class="btn btn-o !px-3" aria-label="{{ __('app.toggle_theme') }}">◐</button>
                    <a href="#dashboard" class="btn !hidden lg:!inline-flex whitespace-nowrap">{{ __('app.nav_dashboard') }}</a>
                    <button id="burger" class="btn btn-o lg:!hidden !px-3" aria-label="{{ __('app.open_menu') }}" aria-expanded="false">☰</button>
                </div>
            </div>

            <nav id="mnav" class="hidden lg:hidden border-t bd px-4 py-3 flex-col gap-3 text-sm font-medium" aria-label="Mobile"></nav>
        </header>

        <main id="app" class="flex-1 relative" tabindex="-1">
            @yield('content')
        </main>

        <footer class="border-t bd mt-16">
            <div class="max-w-6xl mx-auto px-4 py-12 grid gap-10 sm:grid-cols-3 text-sm">
                <div>
                    <p class="font-bold text-lg">Nexus<span style="color: var(--br)">Dig</span></p>
                    <p class="mut mt-3">{{ __('app.footer_tagline') }}</p>
                    <div class="flex gap-3 mt-4">
                        <a href="#contact" class="mut hover:text-[var(--br)] transition-colors">{{ __('app.contact_us') }}</a>
                        <a href="#" class="mut hover:text-[var(--br)] transition-colors">{{ __('app.privacy') }}</a>
                        <a href="#" class="mut hover:text-[var(--br)] transition-colors">{{ __('app.terms') }}</a>
                    </div>
                </div>
                <div>
                    <p class="font-semibold mb-3">{{ __('app.explore') }}</p>
                    <ul class="space-y-1.5 mut">
                        <li><a href="#courses" class="hover:text-[var(--br)] transition-colors">{{ __('app.nav_courses') }}</a></li>
                        <li><a href="#services" class="hover:text-[var(--br)] transition-colors">{{ __('app.nav_services') }}</a></li>
                        <li><a href="#analyzer" class="hover:text-[var(--br)] transition-colors">{{ __('app.nav_analyzer') }}</a></li>
                        <li><a href="#contact" class="hover:text-[var(--br)] transition-colors">{{ __('app.nav_contact') }}</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold mb-3">{{ __('app.legal') }}</p>
                    <ul class="space-y-1.5 mut">
                        <li><a href="#" class="hover:text-[var(--br)] transition-colors">{{ __('app.privacy_policy') }}</a></li>
                        <li><a href="#" class="hover:text-[var(--br)] transition-colors">{{ __('app.terms_conditions') }}</a></li>
                        <li><a href="#" class="hover:text-[var(--br)] transition-colors">{{ __('app.refund_policy') }}</a></li>
                    </ul>
                </div>
            </div>
            <p class="text-center text-xs mut pb-24 lg:pb-8">{{ __('app.copyright', ['year' => now()->year]) }}</p>
        </footer>

        <nav class="fixed bottom-0 left-0 right-0 z-50 border-t bd lg:hidden" style="background: color-mix(in srgb, var(--bg) 85%, transparent); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); padding-bottom: env(safe-area-inset-bottom, 0px)">
            <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between gap-1">
                <a href="#home" class="tab {{ request()->path() === '/' ? 'active' : '' }}" aria-label="{{ __('app.nav_home') }}">🏠<span>{{ __('app.nav_home') }}</span></a>
                <a href="#courses" class="tab {{ str_starts_with(request()->path(), '/courses') ? 'active' : '' }}" aria-label="{{ __('app.nav_courses') }}">🎓<span>{{ __('app.nav_courses') }}</span></a>
                <a href="#services" class="tab" aria-label="{{ __('app.nav_services') }}">🛠️<span>{{ __('app.nav_services') }}</span></a>
                <a href="#dashboard" class="tab" aria-label="{{ __('app.nav_student') }}">📊<span>{{ __('app.nav_student') }}</span></a>
                <a href="#contact" class="tab" aria-label="{{ __('app.nav_contact') }}">✉️<span>{{ __('app.nav_contact') }}</span></a>
            </div>
        </nav>

        <div id="toast" role="status" aria-live="polite" class="fixed bottom-[90px] left-1/2 -translate-x-1/2 z-[60] card px-4 py-2 text-sm shadow-lg hidden"></div>

        @stack('scripts')

        <script>
            if ('serviceWorker' in navigator) {
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
