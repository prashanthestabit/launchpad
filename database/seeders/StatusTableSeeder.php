<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('status')->insert([
            ['name' => 'pending'],
            ['name' => 'approved'],
            ['name' => 'rejected'],
        ]);
    }
}
