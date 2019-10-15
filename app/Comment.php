<?php

namespace App;

use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes, Taggable;

    protected $fillable = [
        'user_id',
        'content'
    ];

    protected $hidden = [
        'deleted_at',
        'commentable_type',
        'commentable_id',
        'user_id'
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
}
