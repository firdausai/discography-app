<?php

namespace App\Http\Controllers;

use App\Modules\Album\AlbumService;
use App\Http\Requests\storeAlbum;
use App\Http\Requests\storeSong;
use App\Http\Requests\editAlbumInfo;
use App\Http\Requests\editSong;
use App\Http\Requests\editAlbumCover;
use App\Http\Requests\deleteSong;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @var $albumService
     */
    protected $albumService;

    /**
     * AlbumRepository constructor
     * 
     * @param AlbumService $albumService
     */
    public function __construct(AlbumService $albumService)
    {
        $this->albumService = $albumService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payload = [
            'currentAlbum' => session('currentAlbum')
        ];

        try {
            $response = $this->albumService->getAlbumsAndDetail($payload);
        } catch (Throwable $e) {
            abort(404);
        }
        
        return view('pages.dashboard')->with($response);
    }

    /**
     * Store a newly created pictures in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePictures(Request $request)
    {
        //
    }

    /**
     * Store a newly created album information in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAlbumInfo(storeAlbum $request)
    {
        $payload = $request->validated();
        
        try {
            $this->albumService->storeAlbum($payload);
        } catch (Throwable $e) {
            abort(404);
        }

        return redirect('dashboard')->with('status', true);
    }

    /**
     * Store a newly created song in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSong(storeSong $request)
    {
        $payload = $request->validated();
        
        try {
            $this->albumService->storeSong($payload);
        } catch (Throwable $e) {
            abort(404);
        }

        return redirect('dashboard')->with('status', true)->with('currentAlbum', $payload['album_id']);
    }

    /**
     * Display the specified song.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSong()
    {
        $payload = [
            'id' => $_GET['id']
        ];
        
        $response = $this->albumService->getSong($payload);

        return response()->json($response);
    }

    /**
     * Display the specified album detail.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showDetail()
    {
        $payload = [
            'id' => $_GET['id']
        ];
        
        $response = $this->albumService->getDetails($payload);

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePictures(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAlbumInfo(editAlbumInfo $request)
    {
        $payload = $request->validated();

        try {
            $response = $this->albumService->updateAlbumInfo($payload);
        } catch (Throwable $e) {
            abort(404);
        }

        return redirect('dashboard')->with('status', true)->with('currentAlbum', $response['album_id']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSong(editSong $request)
    {
        $payload = $request->validated();
        
        try {
            $response = $this->albumService->updateSong($payload);
        } catch (Throwable $e) {
            abort(404);
        }
        
        return redirect('dashboard')->with('status', true)->with('currentAlbum', $response['album_id']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAlbumCover(editAlbumCover $request)
    {
        $payload = $request->validated();
        
        try {
            $response = $this->albumService->editAlbumCover($payload);
        } catch (Throwable $e) {
            abort(404);
        }
        
        return redirect('dashboard')->with('status', true)->with('currentAlbum', $payload['id']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyPictures($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSong(deleteSong $request)
    {
        $payload = $request->validated();
        
        try {
            $response = $this->albumService->deleteSong($payload);
        } catch (Throwable $e) {
            abort(404);
        }
        
        return redirect('dashboard')->with('status', true)->with('currentAlbum', $payload['album_id']);
    }
}
