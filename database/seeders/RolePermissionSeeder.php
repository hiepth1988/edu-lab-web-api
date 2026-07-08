<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'content.view',
            'content.create',
            'content.update',
            'content.delete',
            'content.publish',
            'leads.view',
            'leads.manage',
            'settings.manage',
            'users.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions($permissions);

        $editor = Role::findOrCreate('editor', 'web');
        $editor->syncPermissions([
            'content.view',
            'content.create',
            'content.update',
            'leads.view',
        ]);
    }
}
