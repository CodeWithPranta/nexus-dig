<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses Asia/Dhaka, English and USD by default', function () {
    expect(config('app.timezone'))->toBe('Asia/Dhaka')
        ->and(config('app.locale'))->toBe('en')
        ->and(config('currency.default'))->toBe('USD');
});

it('renders the home page with English content by default', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Online learning', false);
});

it('switches the locale to Bangla', function () {
    $this->get('/locale/bn')->assertRedirect();

    expect(session('locale'))->toBe('bn');

    $this->get('/')
        ->assertOk()
        ->assertSee('অনলাইন লার্নিং', false);
});

it('switches the currency to BDT', function () {
    $this->get('/currency/BDT')->assertRedirect();

    expect(session('currency'))->toBe('BDT');
});

it('shows the target currency on the header toggle', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('→ BDT', false);

    $this->get('/currency/BDT')->assertRedirect();

    $this->get('/')
        ->assertOk()
        ->assertSee('→ USD', false);
});

it('rejects unsupported locales and currencies', function () {
    $this->get('/locale/fr')->assertNotFound();
    $this->get('/currency/EUR')->assertNotFound();
});
