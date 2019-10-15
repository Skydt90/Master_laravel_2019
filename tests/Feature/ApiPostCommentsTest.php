<?php

namespace Tests\Feature;

use App\BlogPost;
use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiPostCommentsTest extends TestCase
{
    use RefreshDatabase;
   
    public function testNewBlogPostDoesNotHaveComments()
    {
        $this->blogPost();
        $response = $this->json('GET', 'api/v1/post/1/comments');
        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(0, 'data');
    }

    public function testBlogPostHas10Comments()
    {
        $this->blogPost()->each(function(BlogPost $post) {
            $post->comments()->saveMany(
                factory(Comment::class, 10)->make([
                    'user_id' => $this->user()->id
                ])
            );
        });
        $response = $this->json('GET', 'api/v1/post/2/comments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'comment_id',
                        'content',
                        'created_at',
                        'updated_at',
                        'user' => [
                            'id',
                            'name'
                        ]
                    ]
                ], 
                'links', 
                'meta'])
            ->assertJsonCount(10, 'data');
    }

    public function testAddingCommentsWhenNotAuthenticated()
    {
        $this->blogPost();

        $response = $this->json('POST', 'api/v1/post/3/comments', [
            'content' => 'hello'
        ]);

        $response->assertStatus(401);
    }

    public function testAddingCommentsWhenAuthenticated()
    {
        $this->blogPost();

        $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/post/4/comments', [
            'content' => 'hello'
        ]);

        $response->assertStatus(201);
    }

    public function testAddingCommentWithInvalidData()
    {
        $this->blogPost();

        $response = $this->actingAs($this->user(), 'api')->json('POST', 'api/v1/post/5/comments', []);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'content' => [
                        'The content field is required.'
                    ]
                ]
            ]);
    }
}
