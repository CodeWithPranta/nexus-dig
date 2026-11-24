<?php

use App\Enums\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

it('renders the login and register pages', function () {
    $this->get('/login')->assertOk()->assertSee(__('app.login_title'));
    $this->get('/register')->assertOk()->assertSee(__('app.register_title'));
});

it('renders password visibility toggles on auth forms', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('data-toggle-password="password"', false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('data-toggle-password="password"', false)
        ->assertSee('data-toggle-password="password_confirmation"', false);
});

it('redirects guests from the dashboard to login', function () {
    $this->get('/dashboard')->assertRedirect(route('login'));
});

it('registers a student and logs them in', function () {
    $this->post('/register', [
        'name' => 'New Student',
        'email' => 'new-student@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();

    $user = User::where('email', 'new-student@example.com')->firstOrFail();

    expect($user->role)->toBe(Role::Student)
        ->and($user->status)->toBe('active')
        ->and(Hash::check('password123', $user->password))->toBeTrue();
});

it('logs in an active user and records the last login time', function () {
    $user = User::factory()->student()->create([
        'email' => 'learner@example.com',
        'password' => 'password',
    ]);

    $this->post('/login', [
        'email' => 'learner@example.com',
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);

    expect($user->fresh()->last_login_at)->not->toBeNull();
});

it('rejects invalid credentials', function () {
    $user = User::factory()->student()->create([
        'email' => 'invalid@example.com',
        'password' => 'password',
    ]);

    $this->from('/login')
        ->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('rejects suspended accounts', function () {
    $user = User::factory()->student()->suspended()->create([
        'email' => 'suspended@example.com',
        'password' => 'password',
    ]);

    $this->from('/login')
        ->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('throttles repeated failed login attempts', function () {
    $user = User::factory()->student()->create(['email' => 'throttle@example.com']);

    foreach (range(1, 5) as $attempt) {
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
    }

    $this->from('/login')
        ->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs out suspended users with an existing session', function () {
    $user = User::factory()->student()->suspended()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('logs out authenticated users', function () {
    $user = User::factory()->student()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

it('sends a password reset link', function () {
    Notification::fake();

    $user = User::factory()->student()->create(['email' => 'reset-link@example.com']);

    $this->post('/forgot-password', ['email' => $user->email])
        ->assertSessionHas('status');

    Notification::assertSentTo($user, ResetPassword::class);
});

it('resets the password with a valid token', function () {
    $user = User::factory()->student()->create(['email' => 'reset@example.com']);
    $token = Password::createToken($user);

    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertRedirect(route('login'));

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
});

it('allows teachers into the admin panel and blocks suspended staff', function () {
    $this->actingAs(User::factory()->teacher()->create())
        ->get('/admin')
        ->assertOk();

    $this->actingAs(User::factory()->superAdmin()->suspended()->create())
        ->get('/admin')
        ->assertForbidden();
});
