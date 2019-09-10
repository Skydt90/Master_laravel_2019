<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //command inherited from Seeder class
        $usersCount = (int)$this->command->ask('How many users would you like?', 30);
        
        //user seeds
        factory(User::class)->states('christian')->create(); //creates and saves the user instance instantly
        factory(User::class, $usersCount)->create();
    } 

}
