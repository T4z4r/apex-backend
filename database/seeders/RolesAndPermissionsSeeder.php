<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $permissions = [
            'manage properties',
            'manage units',
            'manage leases',
            'manage maintenance',
            'manage disputes',
            'manage agents',
            'manage users',
            'view dashboard'
        ];


        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }


        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());


        $landlord = Role::firstOrCreate(['name' => 'landlord']);
        $landlord->givePermissionTo(['manage properties', 'manage units', 'manage leases', 'manage maintenance', 'view dashboard']);


        $tenant = Role::firstOrCreate(['name' => 'tenant']);
        $tenant->givePermissionTo(['manage leases', 'manage maintenance', 'view dashboard']);


        $agent = Role::firstOrCreate(['name' => 'agent']);
        $agent->givePermissionTo(['manage properties', 'manage leases', 'view dashboard']);
    }
}
