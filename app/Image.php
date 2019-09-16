<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getUrl()
    {
        return Storage::url($this->path);
    }
}
