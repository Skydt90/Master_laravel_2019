<?php

use App\BlogPost;
use App\User;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //fetch all users for foreign key relationships.
        $users = User::all();

        //safeguard against empty users table.
        if($users->count() === 1) {
            $this->command->info('There are no users, so no Blog Posts can be created. Please seed users table first!');
            return;
        }

        //prompts user for how many instances should be seeded
        $postCount = (int)$this->command->ask('How many Blog Posts would you like?', 150);
        
        //make creates instances but doesn't save them. 
        //use enables usage of vars outside closure scope.
        //each is a function called on the collection object, returned by the factory.
        factory(BlogPost::class, $postCount)->make()->each(function($post) use ($users) {

            //random is a function available to collections, that returns a random object form the collection
            //id can therefore be called on the object.
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
