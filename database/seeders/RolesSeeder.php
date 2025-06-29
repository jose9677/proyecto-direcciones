<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'description' => 'ADMINISTRADOR',
            'active' => true
        ]);

        Role::create([
            'description' => 'ANALISTA',
            'active' => true
        ]);

        Role::create([
            'description' => 'CONSULTA',
            'active' => true
        ]);
    }
}
