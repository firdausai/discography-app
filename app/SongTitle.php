<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SongTitle extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function song()
    {
        return $this->belongsTo('App\Song', 'id', 'song_title_id');
    }
}
