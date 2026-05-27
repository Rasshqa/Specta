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
        User::updateOrCreate(
            ['email' => 'admin@spectaxxi.sch.id'],
            [
                'name'     => 'Admin SPECTA XXI',
                'email'    => 'admin@spectaxxi.sch.id',
                'password' => Hash::make('specta2025admin'),
                'role'     => 'admin',
            ]
        );

        $this->command->info('✅ Akun admin berhasil dibuat!');
        $this->command->info('   Email   : admin@spectaxxi.sch.id');
        $this->command->info('   Password: specta2025admin');
        $this->command->warn('   ⚠️  Segera ganti password setelah login pertama!');
    }
}
