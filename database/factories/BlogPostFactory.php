<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BlogPost;
use Faker\Generator as Faker;

$factory->define(BlogPost::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(10),
        'content' => $faker->paragraphs(5, true)
    ];
});

// second param is name beig called by PostTest
$factory->state(BlogPost::class, 'test-post', function(Faker $faker) {
    return [
        'title' => 'Test Post',
        'content' => 'Content of test blog post'
    ];
});