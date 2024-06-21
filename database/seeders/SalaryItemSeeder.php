<?php

namespace Database\Seeders;

use App\Models\SalaryItem;
use Illuminate\Database\Seeder;

class SalaryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salary_items = [
            ['type' => 'Earnings', 'name' => 'Accomodation'],
            ['type' => 'Earnings','name' => 'Transport'],
            ['type' =>'Deductions','name' => 'PAYE'],
        ];
        SalaryItem::insert($salary_items);
    }
}
