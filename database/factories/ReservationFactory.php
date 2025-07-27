<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1, // vytvoří uživatele automaticky
            'table_id' => Table::factory(), // vytvoří stůl automaticky
            'datetime' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
        ];
    }

}
