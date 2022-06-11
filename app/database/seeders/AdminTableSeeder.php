<?php

namespace Database\Seeders;

use Hash;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'name' => 'admin',
            'mobile' => 324234, 
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'random_password' =>'123456789'
        ]);
         
        $role = Role::find(1);

        $permissions = Permission::pluck('id', 'id')->all();
   
        $role->syncPermissions($permissions);
     
        $admin->assignRole([$role->id]);
    }
}