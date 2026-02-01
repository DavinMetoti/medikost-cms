<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * =========================
         * PERMISSIONS
         * =========================
         */
        $permissions = [

            // ===== Landing Page =====
            'view landing page',
            'edit landing page',

            'view about page',
            'edit about page',

            'view facilities',
            'edit facilities',

            'view gallery',
            'edit gallery',

            'view location',
            'edit location',

            'view contact',
            'edit contact',

            // ===== Kos (Main Product) =====
            'view kos',
            'create kos',
            'edit kos',
            'delete kos',

            // ===== Kamar =====
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'update room status',
            'manage pricing',

            // ===== Landing Page Sections =====
            'view hero section',
            'edit hero section',

            'view advantages',
            'edit advantages',

            'view testimonials',
            'edit testimonials',

            'view faq',
            'edit faq',

            // ===== SEO & Publish =====
            'view seo',
            'edit seo',
            'publish content',
            'unpublish content',

            // ===== Media Manager =====
            'view media',
            'upload media',
            'delete media',

            // ===== User Management =====
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /**
         * =========================
         * ROLES
         * =========================
         */

        // Super Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Pengelola kos
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // Editor konten
        $editorRole = Role::firstOrCreate(['name' => 'editor']);

        // Viewer / Read-only
        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);

        /**
         * =========================
         * ROLE → PERMISSION
         * =========================
         */

        // Admin: ALL ACCESS
        $adminRole->syncPermissions(Permission::all());

        // Manager Kos
        $managerRole->syncPermissions([
            'view kos',
            'create kos',
            'edit kos',

            'view rooms',
            'create rooms',
            'edit rooms',
            'update room status',
            'manage pricing',

            'view landing page',
            'edit landing page',

            'view facilities',
            'edit facilities',

            'view gallery',
            'edit gallery',

            'view location',
            'edit location',

            'publish content',
        ]);

        // Content Editor
        $editorRole->syncPermissions([
            'view landing page',
            'edit landing page',

            'view hero section',
            'edit hero section',

            'view advantages',
            'edit advantages',

            'view testimonials',
            'edit testimonials',

            'view faq',
            'edit faq',

            'view gallery',
            'edit gallery',

            'view seo',
            'edit seo',
        ]);

        // Viewer (read only)
        $viewerRole->syncPermissions([
            'view landing page',
            'view kos',
            'view rooms',
            'view gallery',
            'view facilities',
        ]);
    }
}
