<?php

namespace App\Providers\Filament;

use App\Http\Middleware\SetCurrency;
use App\Http\Middleware\SetLocale;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('NexusDig')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->navigationGroups([
                NavigationGroup::make('Education'),
                NavigationGroup::make('Commerce'),
                NavigationGroup::make('Services & CRM'),
                NavigationGroup::make('Content & SEO'),
                NavigationGroup::make('System'),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->userMenuItems([
                Action::make('locale_bn')
                    ->label('বাংলা')
                    ->icon('heroicon-o-language')
                    ->url(fn (): string => route('locale.switch', 'bn')),
                Action::make('locale_en')
                    ->label('English')
                    ->icon('heroicon-o-language')
                    ->url(fn (): string => route('locale.switch', 'en')),
                Action::make('currency_bdt')
                    ->label('৳ BDT')
                    ->icon('heroicon-o-banknotes')
                    ->url(fn (): string => route('currency.switch', 'BDT')),
                Action::make('currency_usd')
                    ->label('$ USD')
                    ->icon('heroicon-o-banknotes')
                    ->url(fn (): string => route('currency.switch', 'USD')),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetLocale::class,
                SetCurrency::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
