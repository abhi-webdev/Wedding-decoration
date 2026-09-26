<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdityaUtsavSeeder::class);
        $this->call(Phase5Seeder::class);
        $this->call(AdminSeeder::class);
        $this->call(Phase8VideoSeeder::class);
    }
}
