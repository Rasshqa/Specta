<?php

namespace Database\Seeders;

use App\Models\Merchandise;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        // -------------------------------------------------------
        // USERS — 1 Admin + 2 Gatekeepers
        // -------------------------------------------------------
        User::create([
            'name'     => 'Admin SPECTA',
            'email'    => 'admin@specta-xxi.com',
            'password' => Hash::make('Specta@Admin2026!'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Gatekeeper 1',
            'email'    => 'gate1@specta-xxi.com',
            'password' => Hash::make('Specta@Gate12026!'),
            'role'     => 'gatekeeper',
        ]);

        User::create([
            'name'     => 'Gatekeeper 2',
            'email'    => 'gate2@specta-xxi.com',
            'password' => Hash::make('Specta@Gate22026!'),
            'role'     => 'gatekeeper',
        ]);

        // -------------------------------------------------------
        // TICKETS
        // -------------------------------------------------------
        Ticket::create([
            'ticket_name'     => 'Presale 1',
            'price'           => 50000,
            'quota'           => 500,
            'remaining_quota' => 500,
        ]);

        Ticket::create([
            'ticket_name'     => 'VIP',
            'price'           => 100000,
            'quota'           => 100,
            'remaining_quota' => 100,
        ]);

        // -------------------------------------------------------
        // MERCHANDISES — 2 placeholder items
        // -------------------------------------------------------
        Merchandise::create([
            'name'        => 'SPECTA XXI T-Shirt',
            'price'       => 75000,
            'image'       => null,
            'description' => 'Kaos eksklusif SPECTA XXI: REVELIORA dengan desain bertema Celestial Treasure. Bahan premium, sablon DTF, dan tersedia berbagai ukuran.',
        ]);

        Merchandise::create([
            'name'        => 'SPECTA XXI Totebag',
            'price'       => 45000,
            'image'       => null,
            'description' => 'Totebag kanvas eksklusif SPECTA XXI: REVELIORA. Desain cyberpunk neon purple, cocok untuk daily use.',
        ]);
    }
}
