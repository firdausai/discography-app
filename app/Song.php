<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Song extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the audio path.
     *
     * @param  string  $value
     * @return string
     */
    public function getAudioPathAttribute($value)
    {
        return asset(Storage::url($value));
    }

    public function title()
    {
        return $this->hasOne('App\SongTitle', 'id', 'song_title_id');
    }

    public function arranger()
    {
        return $this->hasOne('App\Arranger', 'id', 'arranger_id');
    }

    public function band()
    {
        return $this->hasOne('App\Band', 'id', 'band_id');
    }

    public function bandLeader()
    {
        return $this->hasOne('App\BandLeader', 'id', 'band_leader_id');
    }

    public function singer()
    {
        return $this->hasOne('App\Singer', 'id', 'singer_id');
    }

    public function songWriter()
    {
        return $this->hasOne('App\SongWriter', 'id', 'song_writer_id');
    }

    public function album()
    {
        return $this->belongsTo('App\Album', 'album_id', 'id');
    }
}
