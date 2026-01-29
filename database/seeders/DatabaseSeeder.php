<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      
    $this->call([
        UserRoleSeeder::class,
    ]);

        // 1. Akun ADMIN (Pembuat Surat)
        User::create([
            'name' => 'Administrator Audit',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // Password sama semua: "password"
            'role' => 'admin',
        ]);

        // 2. Akun AUDITI (Penerima Surat Utama)
        User::create([
            'name' => 'Budi Santoso (Auditi)',
            'email' => 'auditi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'auditi',
        ]);

        // 3. Akun AUDITOR (Tembusan)
        User::create([
            'name' => 'Siti Aminah (Auditor)',
            'email' => 'auditor@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'auditor',
        ]);

        // 4. Akun STAFF K3 (Approver & Tembusan)
        User::create([
            'name' => 'Rudi Gunawan (K3)',
            'email' => 'k3@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'k3',
        ]);

        // 5. Akun STAFF UNIT (Tembusan)
        User::create([
            'name' => 'Dewi Persik (Staff Unit)',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // 6. Akun ATASAN STAFF (Tembusan)
        User::create([
            'name' => 'Pak Bos (Atasan)',
            'email' => 'atasan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'atasan_staff',
        ]);
    }
}