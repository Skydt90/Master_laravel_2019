<?php

use App\BlogPost;
use Illuminate\Database\Seeder;
use App\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = BlogPost::all();

        if($posts->count() === 0) {
            $this->command->info('There are no blog posts, so no comments can be created. Please seed blog_posts table first!');
            return;
        }
        
        $commentCount = (int)$this->command->ask('How many comments would you like?', 300);

        factory(Comment::class, $commentCount)->make()->each( function($comment) use ($posts) {

            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
