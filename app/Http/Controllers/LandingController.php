<?php

namespace App\Http\Controllers;

use App\Modules\Album\AlbumService;
use Illuminate\Http\Request;

class LandingController extends Controller
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
        try {
            $response = $this->albumService->getAlbums();
        } catch (Throwable $e) {
            abort(404);
        }
        
        return view('pages.landing')->with('albums', $response[0])->with('total', $response[1]);
    }

    public function getMatchingGlobalSearch(Request $request)
    {
        $payload = $request->query();

        $response = $this->albumService->getMatchingGlobalSearch($payload);
        
        return response()->json($response);
    }

    public function getClickedCategory(Request $request)
    {
        $payload = $request->query();

        $response = $this->albumService->getClickedCategory($payload);
        
        return response()->json($response);
    }

    public function getNextAlbums(Request $request)
    {
        $payload = [
            'row' => (int) $request['row']
        ];
        // dd($payload);
        $response = $this->albumService->getNextAlbums($payload);

        return response()->json($response);
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
        //
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
