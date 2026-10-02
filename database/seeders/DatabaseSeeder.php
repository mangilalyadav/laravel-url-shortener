<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
   public function run(): void
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin'], ['name' => 'superadmin', 'display_name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['name' => 'admin', 'display_name' => 'Admin']);
        $memberRole = Role::firstOrCreate(['name' => 'member'], ['name' => 'member', 'display_name' => 'Member']);
        
        $superAdminUser = User::firstOrCreate(
            [ 'email' => 'demo.super@gmail.com' ],
            [
                'name'    => 'DemoSuper',
                'email'         => 'demo.super@gmail.com',
                'password'      => bcrypt('12345678'),
            ]
        );

        $adminRole = User::firstOrCreate(
            [ 'email' => 'demo.admin@gmail.com' ],
            [
                'name'    => 'TestAdmin',
                'email'         => 'demo.admin@gmail.com',
                'password'      => bcrypt('12345678'),
            ]
        );

        $permissionGroups = [
            'Role Management' => [
                'role-list'     => 'List Role',
                'role-create'   => 'Create Role',
                'role-edit'     => 'Edit Role',
                'role-delete'   => 'Delete Role',
            ],
            'User Management' => [
                'user-list'     => 'List User',
                'user-create'   => 'Create User',
                'user-edit'     => 'Edit User',
                'user-delete'   => 'Delete User',
            ],
            'Permission Management' => [
                'permission-list'     => 'List Permission',
                'permission-create'   => 'Create Permission',
                'permission-edit'     => 'Edit Permission',
                'permission-delete'   => 'Delete Permission',
            ],
            'ShortUrl Management' => [
                'shorturl-list'     => 'List ShortUrl',
                'shorturl-create'   => 'Create ShortUrl',
                'shorturl-edit'     => 'Edit ShortUrl',
                'shorturl-delete'   => 'Delete ShortUrl',
            ],
            'Invite Management' => [
                'invite-list'     => 'List Invite',
                'invite-create'   => 'Create Invite',
                'invite-edit'     => 'Edit Invite',
                'invite-delete'   => 'Delete Invite',
            ],
          
        ];

        foreach ($permissionGroups as $permissionGroup => $permissions) {
            $group = PermissionGroup::firstOrCreate(['name' => $permissionGroup], ['name' => $permissionGroup]);
            
            foreach ($permissions as $permission => $displayName) {
                $permission = Permission::firstOrCreate(['name' => $permission], ['name' => $permission, 'display_name' => $displayName, 'group_id' => $group->id]);
            }
        }

        //Fetch all permissions
        $permissions = Permission::pluck('id', 'id')->all();

        //Assign all permission to the developer
        $superAdminRole->syncPermissions($permissions);
        $superAdminUser->assignRole([$superAdminRole->id]);
    }
}
