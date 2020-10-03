<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Album\AlbumService;

class AlbumDashboardController extends Controller
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
        } catch (Throwable $e) {
            abort(404);
        }
        // dd($response);
        
        return view('pages.albumDashboard')->with(['album' => $response]);
    }
}
