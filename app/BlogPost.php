<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;

    //fields able to be filled by eleqouent create method
    protected $fillable = ['title', 'content'];

    //relationship setup
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
