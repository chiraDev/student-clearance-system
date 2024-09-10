<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
                'dep_name' => 'Student',
                'email' => null,
                'parent_department' => null
            ],
            [
                'dep_name' => 'Top level management',
                'email' => null,
                'parent_department' => null
            ],
            [
                'dep_name' => 'Asistant Registar',
                'email' => null,
                'parent_department' => null
            ],
            [
                'dep_name' => 'Head Quarters',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'IT Division',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Log Office',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'FDSS',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Cadet Mess',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Publication',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Sport Division',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Technical Support Office',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],

            
            [
                'dep_name' => 'library',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Account Section',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Help Desk',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
            [
                'dep_name' => 'Enlistment',
                'email' => 'tharindumuramudali@gmail.com',
                'parent_department' => null
            ],
        ]);
    }
}

