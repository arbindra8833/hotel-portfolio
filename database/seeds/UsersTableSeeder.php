<?php

use App\Model\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => "Arbindra",
            'last_name' => "Sah",
            'gender' => "Male",
            'phone' => "9814738833",
            'address' => "Kathmandu",
            'email' => "arbindra1sah@gmail.com",
            'password' => bcrypt('password'),
            'avatar' => 'girl-1.png',
            'about' => "hello from the other world",
            'role' => 'admin',
            'status' => TRUE,
            'remember_token' => rand(100000000000,9999999999),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        User::factory()->count(30)->create();   
    }
}
