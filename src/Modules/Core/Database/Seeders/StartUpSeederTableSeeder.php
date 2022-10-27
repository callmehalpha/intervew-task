<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class StartUpSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();


        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::findOrCreate('edit user');
        Permission::findOrCreate('create user');
        Permission::findOrCreate('view user');
        Permission::findOrCreate('delete user');


        Permission::findOrCreate('edit activity');
        Permission::findOrCreate('create activity');
        Permission::findOrCreate('view activity');
        Permission::findOrCreate('change activity status');


        // create roles and assign existing permissions
        //Role for admin
        $role1 = Role::findOrCreate('admin');
        $role1->givePermissionTo('edit user');
        $role1->givePermissionTo('create user');
        $role1->givePermissionTo('view user');
        $role1->givePermissionTo('delete user');
        $role1->givePermissionTo('edit activity');
        $role1->givePermissionTo('create activity');
        $role1->givePermissionTo('view activity');
        $role1->givePermissionTo('change activity status');



        //Role for users
        $role2 = Role::findOrCreate('user');
        $role2->givePermissionTo('create activity');
        $role2->givePermissionTo('view activity');
        $role2->givePermissionTo('edit activity');



        $user = User::create([
            'name' => 'user001',
            'email' => 'user001@user.com',
            'password' => bcrypt('password'),
            'created_at' => date('d.m.y'),
            'updated_at' => date('d.m.y')
        ]);

        $user->assignRole($role2);


        $userAdmin = User::create(
            [
                'email' => 'admin001@admin.com',
                'name' => 'admin001',
                'password' => bcrypt('password'),
                'created_at' => date('d.m.y'),
                'updated_at' => date('d.m.y')
            ],
        );
        $userAdmin->assignRole($role1);
    }
}
