<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Role::create([
            'name' => 'Karyawan',
        ]);
        
        Role::create([
            'name' => 'Manager',
        ]);
        
        Team::create([
            'name' => 'HRD',
        ]);
        
        Team::create([
            'name' => 'Developer',
        ]);
        
        Team::create([
            'name' => 'Marketing',
        ]);

        User::create([
            'name' => 'Hafid',
            'email' => 'maldiniyusan17@gmail.com',
            'password' => Hash::make('maldini17'),
            'team_id' => '1',
            'role_id' => '2',
        ]);

    }
}
