<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use SoftDeletes;

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
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


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
