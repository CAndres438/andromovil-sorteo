<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@andromovil.com'],
            [
                'name' => 'Admin',
                'lastname' => 'General',
                'id_number' => '1234567890',
                'email' => 'admin@andromovil.com',
                'phone' => '3000000000',
                'department' => 'Cundinamarca',
                'city' => 'Bogotá',
                'habeas_data' => true,
                'is_admin' => true,
                'is_winner' => false,
                'password' => Hash::make(env('ADMIN_PASSWORD')),
            ]
        );
    }
}
