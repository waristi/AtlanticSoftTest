<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->full_name = "Alexander Zuluaga";
        $user->email = "bernardo.az27@gmail.com";
        $user->password =  bcrypt("123456");
        $user->save();
    }
}
