<?php

namespace App\Traits;

use App\Tag;

trait Taggable 
{
    //asigning events to all models using trait
    protected static function bootTaggable()
    {
        //calling tags on model, adding new manytomany with sync having all the tags returned by function
        static::updating(function ($model) {
            $model->tags()->sync(static::findTagsInContent($model->content));
        });

        static::created(function ($model) {
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
    }

    //many to many relationship on implementing models
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    private static function findTagsInContent($content)
    {
        //create an array cointaining filtered strings from content
        preg_match_all('/@([^@]+)@/m', $content, $tags);

        //query all the tags with names from returned tags array
        return Tag::whereIn('name', $tags[1] ?? [])->get();
    }

}