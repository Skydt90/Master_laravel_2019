<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'content'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //subscribe to events from HasEvent.php in model.
    //aka deleting/restoring related models using events.
    public static function boot()
    {
        parent::boot();

        //setting up delete with callback to remove related comments to post.
        static::deleting(function(BlogPost $post) {
            $post->comments()->delete();
        });

        //setting up restore callback to restore soft deleted comments related to post
        static::restoring(function(BlogPost $post) {
            $post->comments()->restore();
        });
    } 
}
