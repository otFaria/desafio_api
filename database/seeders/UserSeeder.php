<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
  
    public function run(): void
    {
        
        DB::table('users')->insert([
            'name' => 'Usuário Fixo Teste',
            'email' => 'usuario@teste.com',
            'password' => Hash::make('senha123'), 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}