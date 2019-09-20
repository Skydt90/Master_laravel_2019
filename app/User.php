<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public const LOCALES = [
        'en' => 'English',
        'es' => 'Español',
        'de' => 'Deutsch'
    ];

    //fields able to be filled by eleqouent create method
    protected $fillable = [
        'name', 'email', 'password',
    ];
    
    //relationships
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsOn()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }


    //local query scopes
    public function scopeWithMostBlogPosts(Builder $query)
    {
        return $query->withCount('blogPosts')->orderBy('blog_posts_count', 'desc');
    }

    public function scopeWithMostBlogPostsLastMonth(Builder $query)
    {
        return $query->withCount(['blogPosts' => function(Builder $query) {
            $query->whereBetween(static::CREATED_AT, [now()->subMonths(1), now()]);
        }])
        ->has('blogPosts', '>=', 2)
        ->orderBy('blog_posts_count', 'desc');
    }

    //fetches all users that has commented on the post
    public function scopeThatHasCommentedOnPost(Builder $query, BlogPost $post)
    {
        //closure since comment can be both BlogPost and User
        return $query->whereHas('comments', function($query) use ($post) {
            
            return $query->where('commentable_id', '=',  $post->id)
                ->where('commentable_type', '=', BlogPost::class);
            
        });
    }

    public function scopeThatIsAdmin(Builder $query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
