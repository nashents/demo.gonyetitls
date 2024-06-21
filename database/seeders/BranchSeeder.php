<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $branches = ['Harare','Mutare'];
        foreach($branches as $branch){
            Branch::create([
                'name' => $branch
            ]);
        }
    }
}
