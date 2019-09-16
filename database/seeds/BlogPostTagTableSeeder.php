<?php

use App\BlogPost;
use App\Tag;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagsCount = Tag::all()->count();

        if(0 === $tagsCount) {
            $this->command->info('No tags found. Skipping assignment of tags to blog posts');
            return;
        }

        $howManyMin = (int)$this->command->ask('Minimum tags on blog posts?', 0);
        $howManyMax = min((int)$this->command->ask('Maximum tags on blog posts?', $tagsCount), $tagsCount);

        BlogPost::all()->each(function(BlogPost $post) use ($howManyMin, $howManyMax) {
            
            $take = random_int($howManyMin, $howManyMax);
            
            $tags = Tag::inRandomOrder()
                ->take($take)
                ->get()
                ->pluck('id');

            $post->tags()->sync($tags);
        });
    }
}
