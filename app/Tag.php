<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //relationship setup
    public function blogPosts()
    {
        return $this->belongsToMany(BlogPost::class)->withTimestamps();
    }
}
