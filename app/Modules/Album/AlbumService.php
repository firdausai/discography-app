<?php

namespace App\Modules\Album;

use App\Modules\Album\AlbumRepository;
use Illuminate\Support\Facades\Storage;
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
                'albumDetail'   => $albumDetail->toArray(),
                'totalAlbums'   => $this->albumRepository->getTotalAlbums(),
                'totalSongs'    => $this->albumRepository->getTotalsongs(),
                'totalDownload' => $this->albumRepository->getAllSongs()->pluck('total_download')->sum()
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
                'songWriter' => $payload['song_writer']
            ];

            $songWriter = $this->albumRepository->storeSongWriter($params);

            $params = [
                'album_id'          => $payload['album_id'],
                'song_writer_id'    => $songWriter['id'],
                'song_title_id'     => $songTitle['id'],
                'arranger_id'       => $arranger['id'],
                'singer_id'         => $singer['id'],
                'band_id'           => $band['id'],
                'band_leader_id'    => $bandLeader['id'],
                'audio_path'        => null,
                'index'             => $payload['song_index'],
                'total_download'    => 0
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
        return DB::transaction(function () use ($payload) {
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

            if ($song['songWriter'] === null || $song['songWriter']['song_writer'] !== $payload['edit_song_writer']) {
                $params = [
                    'songWriter' => $payload['edit_song_writer']
                ];

                $songWriter = $this->albumRepository->getSongWriterExact($params);
                
                if (!$songWriter) {
                    $params = [
                        'songWriter' => $payload['edit_song_writer']
                    ];
        
                    $songWriter = $this->albumRepository->storeSongWriter($params);
                }
                
                $updates['song_writer_id'] = $songWriter['id'];
            }

            if ($song['title']['song_title'] !== $payload['edit_song_title']) {
                $params = [
                    'songTitle' => $payload['edit_song_title']
                ];

                $songTitle = $this->albumRepository->getSongTitleExact($params);

                if (!$songTitle) {
                    $params = [
                        'songTitle' => $payload['edit_song_title']
                    ];
        
                    $songTitle = $this->albumRepository->storeSongTitle($params);
                }

                $updates['song_title_id'] = $songTitle['id'];
            }

            if ($song['singer']['singer'] !== $payload['edit_singer']) {
                $params = [
                    'singer' => $payload['edit_singer']
                ];

                $singer = $this->albumRepository->getSingerExact($params);

                if (!$singer) {
                    $params = [
                        'singer' => $payload['edit_singer']
                    ];
        
                    $singer = $this->albumRepository->storeSinger($params);
                }

                $updates['singer_id'] = $singer['id'];
            }

            if ($song['arranger']['arranger'] !== $payload['edit_arranger']) {
                $params = [
                    'arranger' => $payload['edit_arranger']
                ];

                $arranger = $this->albumRepository->getArrangerExact($params);

                if (!$arranger) {
                    $params = [
                        'arranger' => $payload['edit_arranger']
                    ];
        
                    $arranger = $this->albumRepository->storeArranger($params);
                }

                $updates['arranger_id'] = $arranger['id'];
            }

            if ($song['bandLeader']['band_leader'] !== $payload['edit_band_leader']) {
                $params = [
                    'bandLeader' => $payload['edit_band_leader']
                ];

                $bandLeader = $this->albumRepository->getBandLeaderExact($params);

                if (!$bandLeader) {
                    $params = [
                        'bandLeader' => $payload['edit_band_leader']
                    ];
        
                    $bandLeader = $this->albumRepository->storeBandLeader($params);
                }

                $updates['band_leader_id'] = $bandLeader['id'];
            }

            if ($song['band']['band'] !== $payload['edit_band_name']) {
                $params = [
                    'band' => $payload['edit_band_name']
                ];

                $band = $this->albumRepository->getBandNameExact($params);

                if (!$band) {
                    $params = [
                        'band' => $payload['edit_band_name']
                    ];
        
                    $band = $this->albumRepository->storeBand($params);
                }

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
            // dd($params);
            if (count($params['updates']) !== 0) {
                $this->albumRepository->editSong($params);
            }

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
        return [$this->albumRepository->getAllAlbums()->take(10), $this->albumRepository->getTotalAlbums()];
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

    public function getAlbumName($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getAlbumName($params);
    }

    public function getRecordingCompany($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getRecordingCompany($params);
    }

    public function getSongTitle($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getSongTitle($params);
    }

    public function getSongWriter($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getSongWriter($params);
    }

    public function getSinger($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getSinger($params);
    }

    public function getArranger($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getArranger($params);
    }

    public function getBandLeader($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getBandLeader($params);
    }

    public function getBandName($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        return $this->albumRepository->getBandName($params);
    }

    public function getMatchingGlobalSearch($payload)
    {
        $params = [
            'query' => '%'.$payload['name'].'%'
        ];

        $result = [];

        $result['albumName'] = $this->albumRepository->getAlbumName($params)->pluck('title');
        $result['recordingCompany'] = $this->albumRepository->getRecordingCompany($params)->pluck('recording_company');
        $result['songTitle'] = $this->albumRepository->getSongTitle($params)->pluck('song_title');
        $result['songWriter'] = $this->albumRepository->getSongWriter($params)->pluck('song_writer');
        $result['singer'] = $this->albumRepository->getSinger($params)->pluck('singer');
        $result['arranger'] = $this->albumRepository->getArranger($params)->pluck('arranger');
        $result['bandLeader'] = $this->albumRepository->getBandLeader($params)->pluck('band_leader');
        $result['band'] = $this->albumRepository->getBandName($params)->pluck('band');
        $result['releasedDate'] = $this->albumRepository->getReleasedDate($params)->pluck('released_date');
        
        return $result;
    }

    public function getClickedCategory($payload)
    {
        // $params = [
        //     'query'             => $payload['match'],
        //     'albumName'         => $payload['category'] === 'albumName' ? true : false,
        //     'recordingCompany'  => $payload['category'] === 'recordingCompany' ? true : false,
        //     'songTitle'         => $payload['category'] === 'songTitle' ? true : false,
        //     'songWriter'        => $payload['category'] === 'songWriter' ? true : false,
        //     'singer'            => $payload['category'] === 'singer' ? true : false,
        //     'arranger'          => $payload['category'] === 'arranger' ? true : false,
        //     'bandLeader'        => $payload['category'] === 'bandLeader' ? true : false,
        //     'releasedDate'      => $payload['category'] === 'releasedDate' ? true : false,
        // ];

        $params = [
            'query' => $payload['match'],
            'category' => $payload['category']
        ];

        $result = null;

        if ($params['category'] === 'albumName' || $params['category'] === 'recordingCompany' || $params['category'] === 'releasedDate') {
            if ($params['category'] === 'albumName') {
                $result = $this->albumRepository->getAlbumName($params);
            } else if ($params['category'] === 'recordingCompany') {
                $result = $this->albumRepository->getRecordingCompany($params);
            } else if ($params['category'] === 'releasedDate') {
                $result = $this->albumRepository->getReleasedDate($params);           
            }

            $params = [
                'condition' => []
            ];

            $params['condition'] = $result->map(function ($item, $key) use ($params) {
                return $item['id'];
            })->toArray();
            
            return $this->albumRepository->getAlbumsDetail($params);

        } else {
            $field = null;
            if ($params['category'] === 'arranger') {
                $result = $this->albumRepository->getArranger($params);
                $field = 'arranger_id';
            } else if ($params['category'] === 'band') {
                $result = $this->albumRepository->getBandName($params);
                $field = 'band_id';
            } else if ($params['category'] === 'bandLeader') {
                $result = $this->albumRepository->getBandLeader($params);
                $field = 'band_leader_id';
            } else if ($params['category'] === 'singer') {
                $result = $this->albumRepository->getSinger($params);
                $field = 'singer_id';
            } else if ($params['category'] === 'songTitle') {
                $result = $this->albumRepository->getSongTitle($params);
                $field = 'song_title_id';
            } else if ($params['category'] === 'songWriter') {
                $result = $this->albumRepository->getSongWriter($params);
                $field = 'song_writer_id';
            }

            $params = [
                'field' => $field,
                'condition' => []
            ];

            $params['condition'] = $result->map(function ($item, $key) use ($params) {
                if (!in_array($item['id'], $params['condition'])) {
                    return $item['id'];
                }
            })->toArray();

            $ids = $params['condition'];
            
            $songs = $this->albumRepository->getSongs($params);
            
            $params = [
                'condition' => []
            ];

            $params['condition'] = $songs->map(function ($item, $key) use ($params) {
                if (!in_array($item['album_id'], $params['condition'])) {
                    return $item['album_id'];
                }
            })->toArray();

            $albums = $this->albumRepository->getAlbumsDetail($params);

            $albums->each(function ($albumItem, $albumKey) use ($field, $ids) {
                $albumItem['songs']->each(function ($item, $key) use ($field, $ids, $albumItem) {
                    if (!in_array($item[$field], $ids)) {
                        $albumItem['songs']->pull($key);
                    }
                })->toArray();
            })->toArray();

            return $albums->toArray();
        }
    }

    public function getNextAlbums($payload)
    {
        $params = [
            'skip' => $payload['row'],
            'take' => $payload['row'] + 10
        ];
        // dd($params);
        return [$this->albumRepository->getCertainAlbumsByIds($params), $this->albumRepository->getTotalAlbums()];
    }

    public function deleteAlbum($payload)
    {
        DB::transaction(function () use ($payload) {
            $params = [
                'field' => 'album_id',
                'condition' => [$payload['id']]
            ];
            
            $songs = $this->albumRepository->getSongs($params);
            
            $params = $songs->map(function ($item, $key) {
                return $item['id'];
            })->toArray();
            
            $this->albumRepository->deleteSongs($params);
            
            $params = [
                $payload['id']
            ];
    
            $this->albumRepository->deleteAlbumCoversByAlbumId($params);
    
            $params = [
                $payload['id']
            ];
    
            $this->albumRepository->deleteAlbum($params);
        });
    }

    public function downloadSong($payload)
    {
        $params = [
            'id' => $payload['id']
        ];

        $this->albumRepository->addDownloadCount($params);
    }
}