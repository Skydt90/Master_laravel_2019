<?php

namespace Tests;

use App\User;
use App\BlogPost;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function user()
    {
        return factory(User::class)->create();
    }

    protected function blogPost()
    {
        return factory(BlogPost::class)->create([
            'user_id' => $this->user()->id
        ]);
    }
}
