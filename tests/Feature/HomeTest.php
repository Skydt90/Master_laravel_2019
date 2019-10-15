<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeTest extends TestCase
{
    
    /* public function testHomePageIsDisplayingWords()
    {
        $response = $this->get('/');

        $response->assertSeeText('Welcome to Laravel');
        $response->assertSeeText('This is the content of main page');
    } */

    public function testContactWorkingCorrectly()
    {
        $response = $this->get('/contact');

        $response->assertSeeText('Contact');
        $response->assertSeeText('Contact page content');

    }
}
