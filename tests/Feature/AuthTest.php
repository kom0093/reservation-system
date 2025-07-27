<?php

namespace Tests\Feature;

beforeEach(function () {
    $this->artisan('migrate:fresh');

    $this->seedUsers();
});

// <editor-fold desc="Region: Register">
it('can register a user', function () {
    $response = $this->postJson('/api/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john2@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment(['message' => 'Registrace proběhla úspěšně. Nyní se můžete přihlásit.']);
    $this->assertDatabaseHas('users', ['email' => 'john2@example.com']);
});

it('rejects registration with existing email', function () {
    $response = $this->postJson('/api/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['errors' => [
        'email' => ['Tento E-mail již existuje.']
    ]]);
});

it('rejects registration with passwords do not match', function () {
    $response = $this->postJson('/api/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john2@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret1234',
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment(['errors' => [
            'password_confirmation' => ['Potvrzení hesla a Heslo se musí shodovat.']
        ]]);
});
// </editor-fold desc="Region: Register">

// <editor-fold desc="Region: Login">
it('allows a user to login with valid credentials', function () {
    $response = $this->loginUser();

    $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => [
                'id', 'first_name', 'last_name', 'email', 'created_at'
            ],
        ]);
});

it('rejects login with invalid credentials', function () {
    $response = $this->postJson('/api/auth/login', [
        'email' => 'john@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(400)
        ->assertJson(['message' => 'Chybný email nebo heslo']);
});
// </editor-fold desc="Region: Login">

// <editor-fold desc="Region: Profile">
it('gets a profile request', function () {
    $loginResponse = $this->loginUser();

    $profileResponse = $this->withHeaders([
        'Authorization' => "Bearer " . $loginResponse['access_token']
    ])->getJson('/api/auth/profile');

    $profileResponse->assertStatus(200)
        ->assertJsonStructure([
            'id', 'first_name', 'last_name', 'email', 'created_at'
        ],);
});

it('rejects profile request without token', function () {
    $response = $this->getJson('/api/auth/profile');
    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('rejects profile request with invalid token', function () {
    $response = $this->withHeaders(['Authorization' => 'Bearer invalidtoken'])
        ->getJson('/api/auth/profile');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});
// </editor-fold desc="Region: Profile">

// <editor-fold desc="Region: Logout">
it('successfully logouts user', function () {
    $loginResponse = $this->loginUser();

    $logoutResponse = $this->withHeaders([
        'Authorization' => "Bearer " . $loginResponse['access_token']
    ])->getJson('/api/auth/logout');

    $logoutResponse->assertStatus(200)
        ->assertJson(['message' => 'Odhlášení proběhlo úspěšně']);
});

it('rejects logout, because the user is not logged in', function () {
    $profileResponse = $this->getJson('/api/auth/logout');

    $profileResponse->assertStatus(401)
    ->assertJson(['message' => 'Unauthenticated.']);
});
// </editor-fold desc="Region: Logout">
