<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Attendee;
use App\Models\Conference;
use App\Models\Speaker;
use App\Models\Talk;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Phyo Aung Naing Tun',
            'email' => 'pant@gmail.com',
            'password' => Hash::make(111111)
        ]);
        Conference::factory(10)->create();
        Venue::factory(10)->create();
        Speaker::factory(10)->create();
        Talk::factory(10)->create();
        Attendee::factory(10)->create();
    }
}
