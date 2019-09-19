<?php

use App\BlogPost;
use Illuminate\Database\Seeder;
use App\Comment;
use App\User;

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
        $users = User::all();

        if($posts->count() === 0 || $users->count() === 0) {
            $this->command->info('There are no blog posts or users, so no comments can be created. Please seed blog_posts and users table first!');
            return;
        }
        
        $commentCount = (int)$this->command->ask('How many comments would you like?', 300);

        //comments for blogposts
        factory(Comment::class, $commentCount)->make()->each( function($comment) use ($posts, $users) {

            $comment->commentable_id = $posts->random()->id;
            $comment->commentable_type = BlogPost::class;
            $comment->user_id = $users->random()->id;
            $comment->save();
        });

        //comments for user
        factory(Comment::class, $commentCount)->make()->each( function($comment) use ($users) {

            $comment->commentable_id = $users->random()->id;
            $comment->commentable_type = User::class;
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
