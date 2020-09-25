<?php

namespace App\Modules\Album;

use App\Modules\Album\AlbumRepository;
use DB;

class AlbumService
{
    /**
     * @var $albumRepository
     */
    protected $albumRepository;

    /**
     * AlbumRepository constructor
     * 
     * @param AlbumRepository $albumRepository
     */
    public function __construct(AlbumRepository $albumRepository)
    {
        $this->albumRepository = $albumRepository;
    }

    public function getAlbumsAndDetail($payload)
    {
        $albums = $this->albumRepository->getAllAlbums();
        
        if (count($albums) !== 0) {
            $params = [
                'condition' => [
                    'id',
                    '=',
                    $payload['currentAlbum'] ? $payload['currentAlbum'] : $albums[0]['id']
                ]
            ];
            
            $albumDetail = $this->albumRepository->getAlbumDetail($params);
            
            return [
                'albums'        => $albums->toArray(),
                'albumDetail'   => $albumDetail->toArray()
            ];
        }
        
        return [
            'albums'        => null,
            'albumDetail'   => null
        ];
    }

    public function storeAlbum($payload)
    {
        DB::transaction(function () use ($payload) {
            $album = $this->albumRepository->storeAlbum($payload);
            
            if (isset($payload['album-covers'])) {
                $albumCoverpaths = [];
                $index = 0;
                foreach ($payload['album-covers'] as $file) {
                    $time       = time();
                    $fileName   = 'cover-'.$album['id'].'-'.$index.'-'.$time.'.'.$file->getClientOriginalExtension();
                    $path       = $file->storeAs('public/album-covers', $fileName);

                    array_push($albumCoverpaths, [
                        'album_id'      => $album['id'],
                        'cover_path'    => 'album-covers/'.$fileName
                    ]);

                    $index += 1;
                }
                
                $albumCovers = $this->albumRepository->storeAlbumCovers($albumCoverpaths);
            }
        });
    }

    public function storeSong($payload)
    {
        DB::transaction(function () use ($payload) {

            $params = [
                'songTitle' => $payload['song_title']
            ];

            $songTitle = $this->albumRepository->storeSongTitle($params);

            $params = [
                'arranger' => $payload['arranger']
            ];

            $arranger = $this->albumRepository->storeArranger($params);

            $params = [
                'singer' => $payload['singer']
            ];

            $singer = $this->albumRepository->storeSinger($params);

            $params = [
                'band' => $payload['band_name']
            ];

            $band = $this->albumRepository->storeBand($params);

            $params = [
                'bandLeader' => $payload['band_leader']
            ];

            $bandLeader = $this->albumRepository->storeBandLeader($params);

            $params = [
                'album_id'          => $payload['album_id'],
                'song_title_id'     => $songTitle['id'],
                'arranger_id'       => $arranger['id'],
                'singer_id'         => $singer['id'],
                'band_id'           => $band['id'],
                'band_leader_id'    => $bandLeader['id'],
                'audio_path'        => null,
                'index'             => $payload['song_index'],
            ];

            if (isset($payload['audio_file'])) {
                $file                  = $payload['audio_file'];
                $time                   = time();
                $fileName               = 'audio-'.$payload['album_id'].'-'.$time.'.'.$file->getClientOriginalExtension();
                $path                   = $file->storeAs('public/audio', $fileName);
                $params['audio_path']   = 'audio/'.$fileName;
            }
            
            $song = $this->albumRepository->storeSong($params);
        });
    }

    public function getDetails($payload)
    {
        $params = [
            'condition' => [
                'id',
                '=',
                $payload['id']
            ]
        ];
        
        return $this->albumRepository->getAlbumDetail($params);
    }

    public function updateAlbumInfo($payload)
    {
        DB::transaction(function () use ($payload) {
            $params = [
                'title'                 => $payload['album_name'],
                'recording_company'     => $payload['recording_studio'],
                'released_date'         => $payload['released_date'],
                'description'           => $payload['album_description'],
                'id'                    => $payload['id']
            ];
    
            return $this->albumRepository->updateAlbumInfo($params);
        });
    }

    public function getSong($payload)
    {
        $params = [
            'condition' => [
                'id',
                '=',
                $payload['id']
            ]
        ];

        return $this->albumRepository->getSong($params);
    }

    public function updateSong($payload)
    {
        return DB::transaction(function () use ($payload) {
            $params = [
                'condition' => [
                    'id',
                    '=',
                    $payload['id'],
                ]  
            ];

            $song = $this->albumRepository->getSong($params);

            if ($song['index'] !== (integer) $payload['edit_song_index']) {
                $params = [
                    'id' => $payload['id'],
                    'updates' => [
                        ['index' => $payload['edit_song_index']]
                    ]
                ];
        
                $this->albumRepository->editSong($params);
            }

            $updates = [];

            if ($song['title']['song_title'] !== $payload['edit_song_title']) {
                $params = [
                    'songTitle' => $payload['edit_song_title']
                ];
    
                $songTitle = $this->albumRepository->storeSongTitle($params);
                $updates['song_title_id'] = $songTitle['id'];
            }

            if ($song['singer']['singer'] !== $payload['edit_singer']) {
                $params = [
                    'singer' => $payload['edit_singer']
                ];
    
                $singer = $this->albumRepository->storeSinger($params);
                $updates['singer_id'] = $singer['id'];
            }

            if ($song['arranger']['arranger'] !== $payload['edit_arranger']) {
                $params = [
                    'arranger' => $payload['edit_arranger']
                ];
    
                $arranger = $this->albumRepository->storeArranger($params);
                $updates['arranger_id'] = $arranger['id'];
            }

            if ($song['bandLeader']['band_leader'] !== $payload['edit_band_leader']) {
                $params = [
                    'bandLeader' => $payload['edit_band_leader']
                ];
    
                $bandLeader = $this->albumRepository->storeBandLeader($params);
                $updates['band_leader_id'] = $bandLeader['id'];
            }

            if ($song['band']['band'] !== $payload['edit_band_name']) {
                $params = [
                    'band' => $payload['edit_band_name']
                ];
    
                $band = $this->albumRepository->storeBand($params);
                $updates['band_id'] = $band['id'];
            }

            if (isset($payload['edit_audio_file'])) {
                $file                   = $payload['edit_audio_file'];
                $time                   = time();
                $fileName               = 'audio-'.$payload['album_id'].'-'.$time.'.'.$file->getClientOriginalExtension();
                $path                   = $file->storeAs('public/audio', $fileName);
                $updates['audio_path']  = 'audio/'.$fileName;
            }

            $params = [
                'id' => $payload['id'],
                'updates' => $updates,
            ];
            
            $this->albumRepository->editSong($params);

            return $song;
        });
    }

    public function editAlbumCover($payload)
    {
        DB::transaction(function () use ($payload) {
            if (isset($payload['edit_album_covers'])) {
                $index = 0;
                $albumCoverpaths = [];
                foreach($payload['edit_album_covers'] as $file) {
                    $time                   = time();
                    $fileName               = 'cover-'.$payload['id'].'-'.$index.'-'.$time.'.'.$file->getClientOriginalExtension();
                    $path                   = $file->storeAs('public/album-covers', $fileName);
        
                    array_push($albumCoverpaths, [
                        'album_id'      => $payload['id'],
                        'cover_path'    => 'album-covers/'.$fileName
                    ]);
        
                    $index += 1;
                }
        
                $albumCovers = $this->albumRepository->storeAlbumCovers($albumCoverpaths);
            }
            
            if (isset($payload['delete_pictures']) && isset(array_count_values($payload['delete_pictures'])['1'])) {
                $params = [];
                foreach($payload['delete_pictures'] as $key => $value) {
                    if ($value == 1) {
                        array_push($params, $key);
                    }
                }
                $this->albumRepository->deleteAlbumCovers($params);
            }
        });
    }

    public function deleteSong($payload)
    {
        DB::transaction(function () use ($payload) {
            $params = [
                'id' => $payload['id']
            ];

            $this->albumRepository->deleteSong($params);
        });
    }

    public function getAlbums()
    {
        return $this->albumRepository->getAllAlbums();
    }

    public function getAlbumDetail($payload)
    {
        $params = [
            'condition' => [
                'title',
                '=',
                $payload['albumName']
            ]
        ];

        return $this->albumRepository->getAlbumDetail($params);
    }
}