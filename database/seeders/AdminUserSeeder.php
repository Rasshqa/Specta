<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin 1
        User::updateOrCreate(
            ['email' => 'admin@spectaxxi.sch.id'],
            [
                'name'     => 'Admin SPECTA XXI',
                'email'    => 'admin@spectaxxi.sch.id',
                'password' => Hash::make('specta2026admin'),
                'role'     => 'admin',
            ]
        );

        // Admin 2
        User::updateOrCreate(
            ['email' => 'admin@specta-xxi.com'],
            [
                'name'     => 'Admin SPECTA',
                'email'    => 'admin@specta-xxi.com',
                'password' => Hash::make('Specta@Admin2026!'),
                'role'     => 'admin',
            ]
        );

        $this->command->info('✅ Akun admin berhasil dibuat!');
        $this->command->info('   [Admin 1] Email   : admin@spectaxxi.sch.id | Password: specta2026admin');
        $this->command->info('   [Admin 2] Email   : admin@specta-xxi.com    | Password: Specta@Admin2026!');
        $this->command->warn('   ⚠️  Segera ganti password setelah login pertama!');
    }
}
