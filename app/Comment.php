<?php

namespace App;

use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use SoftDeletes, Taggable;

    protected $fillable = [
        'user_id',
        'content'
    ];

    //relationships
    /* public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    } */

    public function commentable()
    {
        return $this->morphTo('commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //this functionality is being implementied via taggable trait
    /* public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    } */


    //local scopes
    public function scopeLatest(Builder $query)
    {
        $query->orderBy(static::CREATED_AT, 'desc');
    }

    

    public static function boot()
    {
        parent::boot();

        static::creating(function (Comment $comment) {

            if($comment->commentable_type === BlogPost::class) {
                
                Cache::forget("blog-post-{$comment->commentable_id}");
                Cache::forget("mostCommented");
            }
        });

        //adding LatestScope global class to blogpost, 
        //to always order by latest entry.
        //static::addGlobalScope(new LatestScope);
    }

}
