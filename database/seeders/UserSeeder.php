<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'vaibhavgupta3098@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'superadmin')->first()->id,
        ]);
    }
}
