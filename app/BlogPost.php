<?php

namespace App;

use App\Scopes\DeletedAdminScope;

use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes, Taggable;

    //fields able to be filled by eleqouent create method
    protected $fillable = ['title', 'content', 'user_id'];

    //relationship setup
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //this functionality is now being implementied via taggable trait
    /*  public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    } */

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope);
        parent::boot();

        /*
        //  This model has event listeners, which will execute when model is called
        //  They exist inside the BlogPostObserver class. Can also be put here.
        */

        //adding LatestScope global class to blogpost, 
        //to always order by latest entry.
        //static::addGlobalScope(new LatestScope);
    }
    
    //adding local scope to model.
    //prefix with scope
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        // comments_count
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeLatestWithRelations(Builder $query)
    {
        return $query->latest()
            ->withCount('comments')
            ->with('user')
            ->with('tags');
    }
}
