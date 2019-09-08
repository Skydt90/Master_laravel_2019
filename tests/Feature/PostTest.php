<?php

namespace Tests\Feature;

use App\BlogPost;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostsWhenDBEmpty()
    {
        $response = $this->get('/post');
        $response->assertSeeText('No posts yet!');
    }

    public function testSee1BlogPostWhenThereIsOne()
    {
        // Arrange
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/post');

        // Assert
        $response->assertSeeText('Test Post');
        
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Test Post'
        ]);
    }

    public function testStoreValid()
    {
        $params = [
            'title' => 'Valid Title',
            'content' => 'Some valid content for the post.'
        ];

        $this->post('/post', $params)
            ->assertStatus(302) // redirect status
            ->assertSessionHas('success');

        $this->assertEquals(session('success'), 'Post was created!');
    }

    public function testStoreFail()
    {
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        $this->post('/post', $params)
        ->assertStatus(302)     // redirect status
        ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();
        $this->assertEquals($messages['title'][0], 'The title must be at least 5 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdatePost()
    {
         // Arrange
         $post = $this->createDummyBlogPost();

         $this->assertDatabaseHas('blog_posts', $post->toArray());

         $params = [
            'title' => 'A new Post',
            'content' => 'some new content for testing'
        ];

        $this->put("/post/{$post->id}", $params)
        ->assertStatus(302)     // redirect status
        ->assertSessionHas('success');

        $this->assertEquals(session('success'), 'Post was updated successfully!');

        $this->assertDatabaseMissing('blog_posts', $post->toArray()); // looking at original post to confirm that it's missing

        // test if db has the updated post
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'A new Post',
            'content' => 'some new content for testing'
        ]);
    }

    public function testDelete()
    {
        $post = $this->createDummyBlogPost();
        
        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $this->delete("/post/{$post->id}")
            ->assertStatus(302)     // redirect status
            ->assertSessionHas('success');

        $this->assertEquals(session('success'), 'Post was deleted!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());

    }

    private function createDummyBlogPost(): BlogPost
    {
        
        $post = new BlogPost();
        $post->title = 'Test Post';
        $post->content = 'Content of test blog post';
        $post->save();

        return $post;
    }
}
