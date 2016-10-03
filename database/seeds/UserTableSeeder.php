<?php

use Illuminate\Database\Seeder;
use App\User;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //this is dummy user for testing
        $user = new User();
        $user->name = 'testUser';
        $user->email = 'testUser@gmail.com';
        $user->password = 'secret';
        $user->save();
    }
}
