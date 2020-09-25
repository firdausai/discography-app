<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function songs()
    {
        return $this->hasMany('App\Song');
    }

    public function covers()
    {
        return $this->hasMany('App\AlbumCover');
    }
}
