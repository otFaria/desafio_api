<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
   
    public function run(): void
    {
        
        DB::table('plans')->insert([
            'name' => 'Plano Básico',
            'price' => 100.00,
            'quotas' => 10,
            'storage_space_gb' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        DB::table('plans')->insert([
            'name' => 'Plano Pro',
            'price' => 200.00,
            'quotas' => 50,
            'storage_space_gb' => 200,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}