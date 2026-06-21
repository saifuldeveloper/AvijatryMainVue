<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $management = Role::firstOrCreate(['name' => 'Management', 'guard_name' => 'web']);

        // 2. Parse and Create Permissions from original project if file exists, or fallback to core permissions
        $permissions = [];
        $seederPath = 'c:/laragon/www/Avijatry_main_software/database/seeders/PermissionsTableSeeder.php';
        
        if (file_exists($seederPath)) {
            $content = file_get_contents($seederPath);
            preg_match_all('/\'name\' => \'([^\']+)\'/', $content, $matches);
            if (!empty($matches[1])) {
                $permissions = array_values(array_unique($matches[1]));
            }
        }

        // Fallback core permissions list if extraction failed
        if (empty($permissions)) {
            $permissions = [
                'manage bank accounts', 'manage categories', 'manage cheques', 'manage sizes', 'manage colors',
                'manage users', 'manage roles', 'manage gift waste', 'manage shoe waste', 'manage sms settings',
                'manage employees', 'manage expenses', 'manage factories', 'edit factories', 'delete factories',
                'manage gifts', 'manage gift suppliers', 'manage invoices', 'manage loans', 'manage purchases',
                'manage reports', 'manage retail stores', 'manage returns to factory', 'manage returns from retail stores',
                'manage pending returns', 'manage shoes', 'manage transactions', 'edit retail stores', 'edit bank accounts',
                'edit gift suppliers', 'edit employees', 'edit loans', 'edit colors', 'edit expenses', 'edit categories',
                'edit gifts', 'edit invoices', 'edit purchases', 'edit bikrikhata', 'delete retail stores', 'delete bank accounts',
                'delete gift suppliers', 'delete employees', 'delete loans', 'delete colors', 'delete expenses',
                'delete categories', 'delete gifts', 'delete invoices', 'delete purchases', 'delete bikrikhata',
                'edit shoes', 'view shoes', 'manage inventory check', 'delete shoe waste', 'force delete shoe waste',
                'restore deleted shoe waste', 'delete gift waste', 'restore deleted gift waste', 'force delete gift waste',
                'show bikri khata list', 'restore deleted factories', 'force delete factories', 'create factories',
                'create retail store', 'restore deleted retail store', 'force delete retail store', 'restore deleted bank accounts',
                'force delete bank accounts', 'factory closing', 'factory dummy closing', 'retail store closing',
                'retail store dummy closing', 'bank account closing', 'restore gift suppliers', 'force delete gift suppliers',
                'gift suppliers closing', 'gift suppliers dummy closing', 'restore deleted employees', 'force delete employees',
                'employees closing', 'employees dummy closing', 'restore deleted loans', 'force delete loans',
                'restore deleted expense', 'force delete expense', 'restore deleted categories', 'force delete categories',
                'restore deleted colors', 'force delete colors', 'edit sizes', 'delete sizes', 'restore deleted sizes',
                'force delete sizes', 'restore deleted gifts', 'force delete gifts', 'manage commitments'
            ];
        }

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 3. Assign all permissions to super-admin
        $superAdmin->syncPermissions($permissions);

        // 4. Create default super-admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'phone' => '01712345678',
                'password' => Hash::make('12345678'),
            ]
        );

        if (!$admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
        }
    }
}
