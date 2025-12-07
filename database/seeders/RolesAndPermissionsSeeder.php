<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // Permission::create(['name' => 'edit articles']);

        // create roles and assign created permissions

        // Finance
        $financeRole = Role::create(['name' => 'Finance']);
        // $financeRole->givePermissionTo('edit articles');

        // HR (Administrator)
        $adminRole = Role::create(['name' => 'Administrator']);
        
        // Operations
        $opsRole = Role::create(['name' => 'Operations']);

        // Marketing
        $marketingRole = Role::create(['name' => 'Marketing']);

        // Super Admin
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        // $superAdminRole->givePermissionTo(Permission::all());
    }
}
