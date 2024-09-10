<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            
            [
                'reg_no' => 'FOC1000',
                'user_name' => 'Deepika Fernando',
                'email' => 'super000@gmail.com',
                'password' => Hash::make('1234'),
                'dep_id' => 2,
                'is_student' => false,
                'is_management' => false,
                'is_super_admin' => true,
            ],
            [
                'reg_no' => 'KDU001',
                'user_name' => 'Head Quartes',
                'email' => 'HQ@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 4,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU002',
                'user_name' => 'IT Division',
                'email' => 'itivision@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 5,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU003',
                'user_name' => 'Log Office',
                'email' => 'log@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 6,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU004',
                'user_name' => 'FDSS',
                'email' => 'fdss@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 7,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU005',
                'user_name' => 'Cadet Mess',
                'email' => 'cadetmess@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 8,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU006',
                'user_name' => 'Publication',
                'email' => 'publication@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 9,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU007',
                'user_name' => 'Sport Division',
                'email' => 'sport@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 10,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU008',
                'user_name' => 'Technical Support Office',
                'email' => 'tso@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 10,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU009',
                'user_name' => 'Help Desk',
                'email' => 'helpdesk@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 14,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU010',
                'user_name' => 'Enlistment',
                'email' => 'enlistment@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 15,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU011',
                'user_name' => 'Library',
                'email' => 'library@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 12,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
            [
                'reg_no' => 'KDU012',
                'user_name' => 'Account Section',
                'email' => 'account@gmail.com',
                'password' => Hash::make('password'),
                'dep_id' => 13,
                'is_student' => false,
                'is_management' => true,
                'is_super_admin' => false,
            ],
        ]);
    }
}
