<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Administrator;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user =  User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Admin@crud'),
        ]);




        // $a_user = Administrator::create([
        //     'first_name' => 'admin',
        //     'last_name' => 'admin',
        //     'email' => 'aadmin@gmail.com',

        //     'password' => Hash::make('Admin@crud'),
        // ]);


        $user->assignRole(['admin']);
        // $a_user->assignRole(['admin']);
    }


}
