<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::create([
        				'name' => 'admin',
        				'display_name' => 'Admin',
        			]);
        $memberRole = Role::create([
        				'name' => 'member',
        				'display_name' => 'Member',
        			]);

        $userAdmin = User::create([
        				'name' => 'Admin noLibApp',
        				'email' => 'admin@nolibapp.com',
        				'phone_number' => '08787788920',
        				'password' => bcrypt('password')
        			]);
        $userAdmin->attachRole($adminRole);

        $memberAdmin = User::create([
        				'name' => 'Safira',
        				'email' => 'safira@nolibapp.com',
        				'phone_number' => '08787788921',
        				'password' => bcrypt('password')
        			]);
        $memberAdmin->attachRole($memberRole);
    }
}
