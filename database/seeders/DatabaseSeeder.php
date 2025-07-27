<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed users
        for ($number = 1; $number <= 20; $number++) {
            User::create([
                'first_name' => 'Test',
                'last_name' => 'User'.$number,
                'email' => 'test'. $number . '@vue.com',
                'password' => 'test' .$number . '@vue.com',
            ]);
        }

        // seed tables
        $distribution = [
            2 => 3,
            4 => 3,
            8 => 3,
            10 => 1
        ];

        foreach ($distribution as $capacity => $count) {
            for ($i = 1; $i <= $count; $i++) {
                Table::create([
                    'capacity' => $capacity,
                ]);
            }
        }

        // seed reservations
        $tables = Table::all();
        $users = User::all();
        $timeSlots = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];

        // date axis: (today - 15 days) + 15 days
        for ($d = -15; $d < 15; $d++) {
            $date = Carbon::today()->addDays($d);

            foreach ($timeSlots as $time) {
                $datetime = $date->copy()->setTimeFromTimeString($time);
                foreach ($tables as $table) {
                    $randomUser = $users->random();
                    // 50% probability that table is reserved and do not reserve the same time
                    if (random_int(1, 100) <= 50 && !$randomUser->reservations()->whereDatetime($datetime)->exists()) {
                        Reservation::create([
                            'user_id' => $randomUser->getId(),
                            'table_id' => $table->id,
                            'datetime' => $date->copy()->setTimeFromTimeString($time),
                        ]);
                    }
                }
            }
        }
    }
}
