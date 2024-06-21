<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            ['fullname'=>'United States Dollar', 'name'=>'USD','symbol' => '$'],
            ['fullname'=>'Zimbabwean Dollar','name'=>'ZWL','symbol' => '$'],
            ['fullname'=>'South African Rand','name'=>'ZAR', 'symbol' => 'R'],
        ];
        Currency::insert($currencies);
    }
}
