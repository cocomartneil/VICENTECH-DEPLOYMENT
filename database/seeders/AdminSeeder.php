<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user using environment variables with fallbacks
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@church.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin User'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')), // Set via ADMIN_PASSWORD env var in production
                'is_admin' => true,
                'is_staff' => false,
                // Use the migration-defined `gender` column (lowercase). Remove incorrect capitalized key 'Sex'.
                'gender' => env('ADMIN_GENDER', 'male'),
                'birthdate' => env('ADMIN_BIRTHDATE', '1990-01-01'),
            ]
        );

        // You can add more admin accounts here if needed
    }
}