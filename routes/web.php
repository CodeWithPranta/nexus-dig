<?php

use App\Support\Currency;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

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
