<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaystationController;
use App\Http\Controllers\Student\DashboardController;
use App\Support\Currency;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware('auth')
    ->name('dashboard');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/checkout/course/{course:slug}', [CheckoutController::class, 'showCourse'])->name('checkout.course.show');
    Route::post('/checkout/course/{course:slug}', [CheckoutController::class, 'storeCourse'])->name('checkout.course.store');
    Route::get('/checkout/service/{service:slug}', [CheckoutController::class, 'showService'])->name('checkout.service.show');
    Route::post('/checkout/service/{service:slug}', [CheckoutController::class, 'storeService'])->name('checkout.service.store');
    Route::get('/checkout/result/{order}', [CheckoutController::class, 'result'])->name('checkout.result');
});

Route::get('/payments/paystation/callback', [PaystationController::class, 'callback'])
    ->name('payments.paystation.callback');

Route::post('/payments/paystation/ipn', [PaystationController::class, 'ipn'])
    ->name('payments.paystation.ipn');

Route::get('/locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['bn', 'en'], true), 404);

    session(['locale' => $locale]);
    app()->setLocale($locale);

    return redirect()->back();
})->name('locale.switch');

Route::get('/currency/{currency}', function (string $currency) {
    abort_unless(Currency::set($currency), 404);

    return redirect()->back();
})->name('currency.switch');
