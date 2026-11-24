<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the public shell with coral branding, social, chat and service modal hooks', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('--br: #f0726c', false)
        ->assertSee('id="svc-modal"', false)
        ->assertSee('chat-wa', false)
        ->assertSee('chat-ms', false)
        ->assertSee('social-ico', false)
        ->assertSee('wa.me', false)
        ->assertSee('m.me', false);
});

it('exposes the team, client and service detail translations to the frontend', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('"nav_team"', false)
        ->assertSee('"our_team"', false)
        ->assertSee('"who_we_work_with"', false)
        ->assertSee('"service_web_long"', false)
        ->assertSee('"team_m1_role"', false)
        ->assertSee('"chat_whatsapp"', false);
});

it('has team, client and service detail copy in both languages', function () {
    $keys = ['nav_team', 'our_team', 'who_we_work_with', 'service_web_long', 'team_m1_role', 'chat_whatsapp'];

    foreach (['en', 'bn'] as $locale) {
        foreach ($keys as $key) {
            expect(__("app.{$key}", [], $locale))->not->toBe("app.{$key}");
        }
    }
});
