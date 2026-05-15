<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VATRate;

class VATRatesSeeder extends Seeder
{
    public function run()
    {
        $rates = [
            ['rate' => 20.00, 'name' => 'Taux normal', 'is_default' => true],
            ['rate' => 10.00, 'name' => 'Taux intermédiaire', 'is_default' => false],
            ['rate' => 5.50, 'name' => 'Taux réduit', 'is_default' => false],
            ['rate' => 2.10, 'name' => 'Taux super réduit', 'is_default' => false],
            ['rate' => 0.00, 'name' => 'Taux zéro (TVA non applicable)', 'is_default' => false],
        ];

        foreach ($rates as $rate) {
            VATRate::create($rate);
        }
    }
}