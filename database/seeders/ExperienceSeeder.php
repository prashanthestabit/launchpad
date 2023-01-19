<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('experiences')->insert([
            ['name' => 'Less than 1 year'],
            ['name' => '1-3 years'],
            ['name' => '3-5 years'],
            ['name' => '5-10 years'],
            ['name' => 'More than 10 years'],
        ]);
    }
}
