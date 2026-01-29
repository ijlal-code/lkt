<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Data User untuk Testing Workflow
        $users = [
            [
                'name'     => 'Bu Marsani (Staff)',
                'email'    => 'staff@tonasa.com',
                'password' => Hash::make('password'),
                'role'     => 'staff',
            ],
            [
                'name'     => 'Pak Senior Manager',
                'email'    => 'sm@tonasa.com',
                'password' => Hash::make('password'),
                'role'     => 'sm',
            ],
            [
                'name'     => 'Pak Chandra (SMQA)',
                'email'    => 'smqa@tonasa.com',
                'password' => Hash::make('password'),
                'role'     => 'smqa',
            ],
            [
                'name'     => 'Pak GM Audit',
                'email'    => 'gm@tonasa.com',
                'password' => Hash::make('password'),
                'role'     => 'gm',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']], // Elak duplicate jika run banyak kali
                $userData
            );
        }
    }
}