<?php

namespace Tests\Traits;

use App\Models\User;
use App\Models\Table;
use App\Models\Reservation;

trait TestHelperTrait
{
    public ?User $user = null;

    public function loginUser()
    {
        return $this->postJson('/api/auth/login', [
            'email' => $this->user->email,
            'password' => 'secret123',
        ]);
    }

    public function seedUsers(): void
    {
        $this->user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'secret123',
        ]);

        User::factory()->create([
            'email' => 'john@example123.com',
            'password' => 'secret123',
        ]);
    }

    public function seedTablesAndReservations(): void
    {
        Table::factory()->create([
            'capacity' => 2,
        ]);
        Table::factory()->create([
            'capacity' => 4,
        ]);

        Reservation::factory()->create([
            'table_id' => 1,
            'user_id' => 1,
            'datetime' => '2025-07-24 13:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 1,
            'datetime' => '2025-07-25 14:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 1,
            'datetime' => '2025-08-25 14:00:00',
        ]);

        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 11:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 12:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 13:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 14:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 15:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 16:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 17:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 18:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 19:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 20:00:00',
        ]);
        Reservation::factory()->create([
            'table_id' => 2,
            'user_id' => 2,
            'datetime' => '2025-08-27 21:00:00',
        ]);
    }

    // případně další užitečné metody...
}
