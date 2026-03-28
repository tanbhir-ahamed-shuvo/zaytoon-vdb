<?php

namespace Database\Seeders;

use App\Models\FieldOfficer;
use Illuminate\Database\Seeder;

class FieldOfficerSeeder extends Seeder
{
    public function run(): void
    {
        $officers = [
            'KAZI MD. BAIJID',
            'RASHED KHAN MANON',
            'SOURAV CHAKI RUDRO',
            'FOYSAL BEPARI',
            'TASRIF AHMED',
            'MD. FORID HOSSEN',
            'MD. ZAHID HOSSAIN',
            'JANNATUN KHATUN',
            'MD. SADIQUL ISLAM',
            'FIROZ KABIR',
            'MD. MAMUN OR RASHID',
            'DIPBENDU PAUL',
            'MD. BAKUL ISLAM',
            'MD. AHSAN HABIB',
            'MURSALIN ALI',
        ];

        foreach ($officers as $name) {
            FieldOfficer::firstOrCreate(['name' => $name]);
        }
    }
}
