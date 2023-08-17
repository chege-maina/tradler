<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        $roles = ['Admin', 'Sales Rep'];
        for ($i = 0; $i < sizeof($roles); $i++) {
            DB::table('roles')->insert(['role'=>$roles[$i]]);
        }
    }
}
