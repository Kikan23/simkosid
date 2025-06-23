<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus user yang sudah ada jika ada
        User::where('email', 'admin@simkos.com')->delete();
        User::where('email', 'manager@simkos.com')->delete();
        User::where('email', 'staff@simkos.com')->delete();

        // Buat user admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simkos.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        // Buat user manager
        User::create([
            'name' => 'Manager Kos',
            'email' => 'manager@simkos.com',
            'password' => Hash::make('manager123'),
            'email_verified_at' => now(),
        ]);

        // Buat user staff
        User::create([
            'name' => 'Staff Kos',
            'email' => 'staff@simkos.com',
            'password' => Hash::make('staff123'),
            'email_verified_at' => now(),
        ]);

        echo "User admin berhasil dibuat!\n";
        echo "Email: admin@simkos.com | Password: password123\n";
        echo "Email: manager@simkos.com | Password: manager123\n";
        echo "Email: staff@simkos.com | Password: staff123\n";
    }
}