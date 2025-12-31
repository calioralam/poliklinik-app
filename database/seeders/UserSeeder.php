<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'nama' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
            ],
            [
                'nama' => 'Dokter',
                'email' => 'dokter@gmail.com',
                'password' => Hash::make('dokter'),
                'role' => 'dokter',
            ],
            [
                'nama' => 'Pasien',
                'email' => 'pasien@gmail.com',
                'password' => Hash::make('pasien'),
                'role' => 'pasien',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
        ['email' => $user['email']], // cari berdasarkan email
        $user // jika ditemukan, update field lainnya
        );
        }
    }
}
