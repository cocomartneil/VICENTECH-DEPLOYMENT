<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // idempotent: create the staff user only if the email doesn't exist
        User::firstOrCreate(
            ['email' => 'staff@church.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('staff123'),
                'is_staff' => true,
                'is_admin' => false,
            ]
        );
    }
} 