<?php

namespace App;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use SoftDeletes;

    //fields able to be filled by eleqouent create method
    protected $fillable = ['title', 'content', 'user_id'];

    //relationship setup
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    //subscribe to events from HasEvent.php in model.
    //aka deleting/restoring related models using events.
    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope());
        
        parent::boot();

        //setting up delete with callback to remove related comments to post.
        static::deleting(function(BlogPost $post) {
            $post->comments()->delete();
            Cache::forget("blog-post-{$post->id}");
        });

        //setting up restore callback to restore soft deleted comments related to post.
        static::restoring(function(BlogPost $post) {
            $post->comments()->restore();
        });

        //adding LatestScope global class to blogpost, 
        //to always order by latest entry.
        //static::addGlobalScope(new LatestScope());

        //setting updating callback to clear cache
        static::updating(function(BlogPost $post) {
            Cache::forget("blog-post-{$post->id}");
        });
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
