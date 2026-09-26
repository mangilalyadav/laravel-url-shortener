<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MemberUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $memberRole = Role::firstOrCreate([
            'name' => 'member',
            'guard_name' => 'web',
            'display_name' => 'Member'
        ]);

        // Create admin user
        $user = User::updateOrCreate(
            [
                'email' => 'demo.member@gmail.com',
            ],
            [
                'name' => 'Member User',
                'password' => Hash::make('12345678'),
            ]
        );

        // Assign admin role
        $user->assignRole($memberRole);
    }
}
