<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Call individual seeders. Add or remove entries as needed.
        $this->call([
            CertificateTemplateSeeder::class,
            EventRegistrationSeeder::class,
            MassScheduleSeeder::class,
            ParishRecordSeeder::class,
            PriestCalendarSeeder::class,
            SacramentTimeSlotSeeder::class,
            SacramentTypeSeeder::class,
            ServerTypeSeeder::class,
            StaffSeeder::class,
        ]);
    }
}
