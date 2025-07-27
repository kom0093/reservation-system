<?php

namespace Tests\Feature;

use App\Models\Reservation;

beforeEach(function () {
    $this->artisan('migrate:fresh');

    $this->seedUsers();
});

// <editor-fold desc="Region: List">
it('gets reservations list for current user - return only future', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $listResponse = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations?page=1&past=0');

    $listResponse->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['created_at', 'datetime', 'id', 'table_capacity', 'table_id', 'user_id'],
            ],
        ]);
});

it('gets reservations list for current user - include past reservations', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $listResponse = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations?page=1&past=1');

    $listResponse->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => ['created_at', 'datetime', 'id', 'table_capacity', 'table_id', 'user_id'],
            ],
        ]);
});

it('gets reservations list for current user - user has 0 reservations', function () {
    $token = $this->loginUser()['access_token'];

    $listResponse = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations?page=1&past=0');

    $listResponse->assertStatus(200)
        ->assertJsonCount(0, 'data');
});

it('rejects reservations list - user not logged in', function () {
    $listResponse = $this->withHeaders([
        'Authorization' => "Bearer invalidToken",
    ])->getJson('/api/reservations?page=1&past=0');

    $listResponse->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});
// </editor-fold desc="Region: List">

// <editor-fold desc="Region: GetAvailableTimes">
// all available ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
it('gets reservations available times - 10 of 11 are free', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations/get-available-times?date=2025-08-25&person_count=3');

    $response->assertStatus(200)
        ->assertJsonCount(10, 'available_times');
});

it('gets reservations available times - no free times (not a table with capacity of 5)', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations/get-available-times?date=2025-08-25&person_count=5');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'available_times');
});

it('gets reservations available times - no free times (all tables full all day)', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations/get-available-times?date=2025-08-27&person_count=4');

    $response->assertStatus(200)
        ->assertJsonCount(0, 'available_times');
});

it('reject get reservations available times - user not logged in', function () {
    $response = $this->withHeaders([
        'Authorization' => "Bearer invalidToken",
    ])->getJson('/api/reservations/get-available-times?date=2025-08-27&person_count=2');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('rejects get available times - missing date parameter', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations/get-available-times?person_count=3');

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'Pole Datum je povinné.',
        ]);
});

it('rejects get available times - invalid person_count', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->getJson('/api/reservations/get-available-times?date=2025-08-25&person_count=abc');

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'Pole Počet osob musí být číslo.',
        ]);
});
// </editor-fold desc="Region: GetAvailableTimes">

// <editor-fold desc="Region: Save">
it('creates new reservation in DB', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->postJson('/api/reservations', [
        'date' => '2025-07-31',
        'time' => '20:00',
        'person_count' => 3,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Rezervace byla úspěšně vytvořena.',
        ]);
});

it('reject reservation creation in DB - no complete data', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->postJson('/api/reservations', [
        'time' => '20:00',
        'person_count' => 3,
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'Pole Datum je povinné.',
        ]);
});

it('reject reservation creation in DB - all tables are booked', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->postJson('/api/reservations', [
        'date' => '2025-08-25',
        'time' => '14:00',
        'person_count' => 3,
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'V tomto termínu již není volný stůl.',
        ]);
});

it('reject reservation creation in DB - user not logged in', function () {
    $response = $this->withHeaders([
        'Authorization' => "Bearer invalidToken",
    ])->postJson('/api/reservations', [
        'date' => '2025-08-25',
        'time' => '14:00',
        'person_count' => 3,
    ]);

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});
// </editor-fold desc="Region: Save">

// <editor-fold desc="Region: Delete">
it('deletes reservation from DB', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->deleteJson('/api/reservations/1');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Změna byla úspěšně provedena.',
        ]);
});

it('rejects deleting reservation from other user', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->deleteJson('/api/reservations/4');

    $response->assertStatus(403)
        ->assertJson([
            'message' => 'Nemáte oprávnění k této akci.',
        ]);
});

it('rejects deleting reservation that does not exist', function () {
    $this->seedTablesAndReservations();
    $token = $this->loginUser()['access_token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer ".$token,
    ])->deleteJson('/api/reservations/600');

    $response->assertStatus(404);
});

it('reject deleting reservation  - user not logged in', function () {
    $response = $this->withHeaders([
        'Authorization' => "Bearer invalidToken",
    ])->deleteJson('/api/reservations/4');

    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});
// </editor-fold desc="Region: Delete">
