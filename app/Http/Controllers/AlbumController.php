<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Modules\Album\AlbumService;
use Illuminate\Http\Request;

class AlbumController extends Controller
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
    public function index($albumName)
    {
        $payload = [
            'albumName' => str_replace('-', ' ', $albumName)
        ];
        
        try {
            $response = $this->albumService->getAlbumDetail($payload);
            if ($response === null) {
                abort(404);
            }
        } catch (Throwable $e) {
            abort(404);
        }
        // dd($response);
        return view('pages.album')->with('album', $response);
    }

    public function download(Request $request)
    {
        $payload = $request->all();
        
        try {
            $this->albumService->downloadSong($payload);
        } catch (Throwable $e) {
            abort(404);
        }

        return response()->download(public_path(str_replace(asset(''), '', $payload['download'])), $payload['filename'].'.mp3');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // currently here, need to add logic to store new albums
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
