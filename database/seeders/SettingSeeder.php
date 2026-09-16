<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_name' => 'Aetherian Cargo',
            'company_email' => 'atheriancargo@gmail.com',
            'company_phone' => '+1 (423) 277-8587',
            'company_address' => 'Aetherian Cargo HQ',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
