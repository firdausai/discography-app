<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AlbumCover extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the payment proof path.
     *
     * @param  string  $value
     * @return string
     */
    public function getCoverPathAttribute($value)
    {
        return asset(Storage::url($value));
    }
}
