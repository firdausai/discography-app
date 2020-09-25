<?php

namespace App\Modules\Album;

use App\AlbumCover;
use App\BandLeader;
use App\SongTitle;
use App\Arranger;
use App\Singer;
use App\Album;
use App\Band;
use App\Song;

class AlbumRepository
{
    /**
     * @var AlbumCover
     */
    protected $albumCover;

    /**
     * @var BandLeader
     */
    protected $bandLeader;

    /**
     * @var SongTitle
     */
    protected $songTitle;

    /**
     * @var Arranger
     */
    protected $arranger;

    /**
     * @var Singer
     */
    protected $singer;

    /**
     * @var Album
     */
    protected $album;

    /**
     * @var Band
     */
    protected $band;


    /**
     * @var Song
     */
    protected $song;


    /**
     * SubscriptionRepository constructor.
     *
     * @param Album $album
     * @param AlbumCover $albumCover
     */
    public function __construct(
        AlbumCover $albumCover,
        BandLeader $bandLeader,
        SongTitle $songTitle,
        Arranger $arranger,
        Singer $singer,
        Album $album,
        Band $band,
        Song $song
    ) {
        $this->albumCover = $albumCover;
        $this->bandLeader = $bandLeader;
        $this->songTitle = $songTitle;
        $this->arranger = $arranger;
        $this->singer = $singer;
        $this->album = $album;
        $this->band = $band;
        $this->song = $song;
    }

    public function getAllAlbums()
    {
        return $this->album->with([
            'covers',
            'songs',
            'songs.band',
            'songs.title', 
            'songs.singer',
            'songs.arranger', 
            'songs.bandLeader'
            ])->get();
    }

    public function getAlbumDetail($params)
    {
        return $this->album->with([
            'covers',
            'songs',
            'songs.band',
            'songs.title', 
            'songs.singer',
            'songs.arranger', 
            'songs.bandLeader'
            ])->where([$params['condition']])
            ->first();
    }

    public function storeAlbum($params)
    {
        $album                      = new $this->album;
        $album->title               = $params['album-name'];
        $album->recording_company   = $params['recording-studio'];
        $album->released_date       = $params['released-date'];
        $album->description         = $params['album-description'];
        $album->save();

        return $album;
    }

    public function storeAlbumCovers($params)
    {
        return $this->albumCover->insert($params);
    }

    public function storeSong($params)
    {
        $song                  = new $this->song;
        $song->album_id        = $params['album_id'];
        $song->song_title_id   = $params['song_title_id'];
        $song->arranger_id     = $params['arranger_id'];
        $song->singer_id       = $params['singer_id'];
        $song->band_id         = $params['band_id'];
        $song->band_leader_id  = $params['band_leader_id'];
        $song->audio_path      = $params['audio_path'];
        $song->index           = $params['index'];
        $song->save();

        return $song;
    }

    public function storeSongTitle($params)
    {
        $songTitle                  = new $this->songTitle;
        $songTitle->song_title      = $params['songTitle'];
        $songTitle->save();

        return $songTitle;
    }

    public function storeArranger($params)
    {
        $arranger                = new $this->arranger;
        $arranger->arranger      = $params['arranger'];
        $arranger->save();

        return $arranger;
    }

    public function storeSinger($params)
    {
        $singer              = new $this->singer;
        $singer->singer      = $params['singer'];
        $singer->save();

        return $singer;
    }

    public function storeBand($params)
    {
        $band            = new $this->band;
        $band->band      = $params['band'];
        $band->save();

        return $band;
    }

    public function storeBandLeader($params)
    {
        $bandLeader                  = new $this->bandLeader;
        $bandLeader->band_leader     = $params['bandLeader'];
        $bandLeader->save();

        return $bandLeader;
    }

    public function updateAlbumInfo($params)
    {
        $album                      = $this->album->where('id', $params['id'])->first();
        $album->title               = $params['title']              ? $params['title']              : $album['title'];
        $album->recording_company   = $params['recording_company']  ? $params['recording_company']  : $album['recording_company'];
        $album->released_date       = $params['released_date']      ? $params['released_date']      : $album['released_date'];
        $album->description         = $params['description']        ? $params['description']        : $album['description'];
        $album->save();

        return $album;
    }

    public function getSong($params)
    {
        return $this->song->with([
            'band',
            'title', 
            'singer',
            'arranger', 
            'bandLeader'
            ])->where([$params['condition']])
            ->first();
    }

    public function editSong($params)
    {
        return $this->song
            ->where('id', $params['id'])
            ->update($params['updates']);
    }

    public function deleteSong($params)
    {
        $this->song->where('id', $params['id'])->delete();
    }

    public function deleteAlbumCovers($params)
    {
        $this->albumCover->whereIn('id', $params)->delete();
    }
}