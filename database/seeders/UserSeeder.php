<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * =========================
         * SUPER ADMIN
         * =========================
         */
        $admin = User::firstOrCreate(
            ['email' => 'admin@medikost.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles(['admin']);

        /**
         * =========================
         * MANAGER KOS
         * =========================
         */
        $manager = User::firstOrCreate(
            ['email' => 'manager@medikost.id'],
            [
                'name' => 'Manager Kos',
                'password' => Hash::make('password'),
            ]
        );
        $manager->syncRoles(['manager']);

        /**
         * =========================
         * CONTENT EDITOR
         * =========================
         */
        $editor = User::firstOrCreate(
            ['email' => 'editor@medikost.id'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('password'),
            ]
        );
        $editor->syncRoles(['editor']);

        /**
         * =========================
         * VIEWER / READ ONLY
         * =========================
         */
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@medikost.id'],
            [
                'name' => 'Viewer',
                'password' => Hash::make('password'),
            ]
        );
        $viewer->syncRoles(['viewer']);
    }
}
