<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'email' => 'elearningadmin@ueab.ac.ke',
        //     'password' => bcrypt('elearningadmin@ueab.ac.ke'),
        // ]);
        $adminPassword = env('APP_ADMIN_PASSWORD', 'elearningadmin@ueab.ac.ke');
        User::create([
            'email' => env('APP_ADMIN', 'elearningadmin@ueab.ac.ke'),
            'password' => bcrypt($adminPassword),
        ]);
    }
}
