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

        User::create([
            'name' => 'Edsel',
            'email' => 'edsel123@gmail.com',
            'password' => Hash::make('edseledsel'),
            'team_id' => '1',
            'role_id' => '2',
        ]);

        User::create([
            'name' => 'Juljian',
            'email' => 'jianganteng2334@gmail.com',
            'password' => Hash::make('juljian123'),
            'team_id' => '1',
            'role_id' => '2',
        ]);

        User::create([
            'name' => 'Karyawan1',
            'email' => 'karyawan1@gmail.com',
            'password' => Hash::make('karyawan12345'),
            'team_id' => '1',
            'role_id' => '1',
        ]);

        User::create([
            'name' => 'Karyawan2',
            'email' => 'karyawan2@gmail.com',
            'password' => Hash::make('karyawan12345'),
            'team_id' => '2',
            'role_id' => '1',
        ]);

        User::create([
            'name' => 'Karyawan3',
            'email' => 'karyawan3@gmail.com',
            'password' => Hash::make('karyawan12345'),
            'team_id' => '3',
            'role_id' => '1',
        ]);

    }
}
