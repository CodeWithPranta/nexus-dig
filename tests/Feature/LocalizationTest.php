<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses Asia/Dhaka, Bangla and BDT by default', function () {
    expect(config('app.timezone'))->toBe('Asia/Dhaka')
        ->and(config('app.locale'))->toBe('bn')
        ->and(config('currency.default'))->toBe('BDT');
});

it('renders the home page with Bangla content by default', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('অনলাইন লার্নিং', false);
});

it('switches the locale to English', function () {
    $this->get('/locale/en')->assertRedirect();

    expect(session('locale'))->toBe('en');

    $this->get('/')
        ->assertOk()
        ->assertSee('Online learning', false);
});

it('switches the currency to USD', function () {
    $this->get('/currency/USD')->assertRedirect();

    expect(session('currency'))->toBe('USD');
});

it('shows the target currency on the header toggle', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('$ USD', false);

    $this->get('/currency/USD')->assertRedirect();

    $this->get('/')
        ->assertOk()
        ->assertSee('৳ BDT', false);
});

it('rejects unsupported locales and currencies', function () {
    $this->get('/locale/fr')->assertNotFound();
    $this->get('/currency/EUR')->assertNotFound();
});
