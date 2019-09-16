<?php

namespace App;

use App\Scopes\LatestScope;
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
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
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
            Cache::forget("blog-post-{$comment->blog_post_id}");
            Cache::forget("mostCommented");
        });

        //adding LatestScope global class to blogpost, 
        //to always order by latest entry.
        //static::addGlobalScope(new LatestScope);
    }

}
