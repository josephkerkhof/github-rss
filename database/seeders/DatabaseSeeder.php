<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Joseph Kerkhof',
            'email' => 'joseph@kerkhof.dev',
            'password' => Hash::make('Testing123'),
        ]);
    }
}
