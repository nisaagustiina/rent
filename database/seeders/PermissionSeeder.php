<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Now();
         // reset cahced roles and permission
         app()[PermissionRegistrar::class]->forgetCachedPermissions();

         // create permissions
         Permission::create(['name' => 'create users']);
         Permission::create(['name' => 'read users']);
         Permission::create(['name' => 'update users']);
         Permission::create(['name' => 'delete users']);
         Permission::create(['name' => 'create talents']);
         Permission::create(['name' => 'read talents']);
         Permission::create(['name' => 'update talents']);
         Permission::create(['name' => 'delete talents']);

         // gets all permissions via Gate::before rule
         $webdevRole = Role::create(['name' => 'webdev']);

         //create roles and assign existing permissions
         $adminRole = Role::create(['name' => 'admin']);
         $adminRole->givePermissionTo('create users');
         $adminRole->givePermissionTo('read users');
         $adminRole->givePermissionTo('update users');
         $adminRole->givePermissionTo('delete users');
         $adminRole->givePermissionTo('create talents');
         $adminRole->givePermissionTo('read talents');
         $adminRole->givePermissionTo('update talents');
         $adminRole->givePermissionTo('delete talents');
 
         $userRole = Role::create(['name' => 'talent']);
         $userRole->givePermissionTo('create talents');
         $userRole->givePermissionTo('read talents');
         $userRole->givePermissionTo('update talents');
 
         // create users
         $user = User::create([
            'username' => 'webdev',
            'email' => 'webdev@email.com',
            'password' => Hash::make('rahasia'),
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
            'last_login' => $now,
         ]);
         $user->assignRole($webdevRole);

         $user = User::create([
            'username' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin'),
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
            'last_login' => $now,
         ]);
         $user->assignRole($adminRole);

         $user = User::create([
            'username' => 'talent',
            'email' => 'user@email.com',
            'password' => Hash::make('talent'),
            'status' => 1,
            'created_at' => $now,
            'updated_at' => $now,
            'last_login' => $now,
         ]);
         $user->assignRole($userRole);
    }
}
