<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = Role::createOrFirst(['name' => 'owner', 'guard_name' => 'web']);

        $array = [
            'view-any User',
            'view User',
            'create User',
            'update User',
            'delete User',
            'restore User',
            'force-delete User',
            
            'view-any State',
            'view State',
            'create State',
            'update State',
            'delete State',
            'restore State',

            'view-any City',
            'view City',
            'create City',
            'update City',
            'delete City',
            'restore City',

            'view-any Role',
            'view Role',
            'create Role',
            'update Role',
            'delete Role',
            'restore Role',
            'restore-any Role',
            'replicate Role',
            'reorder Role',
            'force-delete Role',
            'force-delete-any Role',
            
            'view-any Permission',
            'view Permission',
            'create Permission',
            'update Permission',
            'delete Permission',
            'restore Permission',
            'restore-any Permission',
            'replicate Permission',
            'reorder Permission',
            'force-delete Permission',
            'force-delete-any Permission',

            'view-any Credential',
            'view Credential',
            'create Credential',
            'update Credential',
            'delete Credential',
            'restore Credential',
            'restore-any Credential',
            'replicate Credential',
            'reorder Credential',
            'force-delete Credential',
            'force-delete-any Credential',
            
        ];

        foreach ($array as $permission) {
            Permission::createOrFirst(['name' => $permission, 'guard_name' => 'web']);
        }

        $owner->givePermissionTo(Permission::all());
    }
}
