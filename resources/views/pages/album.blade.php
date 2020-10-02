@extends('templates.default')
@section('content')
    <section class = "overflow-auto" style = "height:90vh">
        <div class = "container mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home mr-2"></i>Beranda</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $album['title'] }}</li>
                </ol>
            </nav>
            <div id = "album-info-wrapper" class = "container height-35-vh">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div id="album-covers" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @foreach ($album['covers'] as $covers)
                                    @if ($loop->first)
                                        <li data-target="#album-covers" data-slide-to="{{ $loop->index }}" class="active"></li>
                                    @else
                                        <li data-target="#album-covers" data-slide-to="{{ $loop->index }}"></li>
                                    @endif
                                @endforeach
                            </ol>
                            <div class="carousel-inner" style = "max-height: 35vh !important">
                                @foreach ($album['covers'] as $cover)
                                    @if ($loop->first)
                                        <div class="carousel-item active">
                                            <img src="{{ $cover['cover_path'] }}" class="d-block mx-auto rounded" style = "max-height: 35vh !important"alt="...">
                                        </div>
                                    @else
                                        <div class="carousel-item">
                                            <img src="{{ $cover['cover_path'] }}" class="d-block mx-auto rounded" style = "max-height: 35vh !important"alt="...">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#album-covers" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#album-covers" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <h1><strong>{{ $album['title'] }}</strong></h1>
                        <h6>{{ $album['released_date'] }}</h6>
                        <br>
                        <p>
                        {{ strlen($album['description']) > 574 ? substr($album['description'], 0, 572).'...' : $album['description'] }}
                        </p>
                        <button id = "full-description" type="button" class="btn btn-primary btn-sm {{ strlen($album['description']) > 574 ? ' ' : 'd-none' }}" data-toggle="modal" data-target="#exampleModalCenter">Baca Selengkapnya</button>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <form id = "download-song" action="{{ url('album/download') }}" method = "GET">
                    <input type="hidden" id="filename" name="filename" value="">
                @foreach ($album['songs'] as $song)
                    @if ($loop->first)
                        <div class="card shadow-sm" data-id = "{{ $song['id'] }}" data-index = "{{ $loop->iteration }}">
                            <div class="card-body">
                                <div class="row py-2 px-2">
                                    <div id = "{{ 'song-'.$loop->iteration }}" class="col-md-1 col-12 pt-3 pt-md-0 text-center my-auto h4 song-list-play-btn" role = "button" data-name = "{{ $song['title']['song_title'] }}" data-id = "{{ $song['id'] }}" data-name = "{{ $song['title']['song_title'] }}" data-path = "{{ $song['audio_path'] }}">
                                        <span role = "button "><i class="fas fa-play-circle"></i></span>
                                    </div>
                                    <div class="col-md-1 col-12 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Track</small></p>
                                        <p class = "card-text h6">{{ $song['index'] }}</p>
                                    </div>
                                    <div class="col-md-2 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Judul</small></p>
                                        <p class = "card-text h6">{{ $song['title']['song_title'] }}</p>
                                    </div>
                                    <div class="col-md-3 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Penyanyi</small></p>
                                        <p class = "card-text h6">{{ $song['singer']['singer'] }}</p>
                                    </div>
                                    <div class="col-md-2 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Band</small></p>
                                        <p class = "card-text h6">{{ $song['band']['band'] }}</p>
                                    </div>
                                    <div class="col-md-2 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Pencipta</small></p>
                                        <p class = "card-text h6">{{ $song['arranger']['arranger'] }}</p>
                                    </div>
                                    <button class="col-md-auto col-12 pt-3 pt-md-0 text-center my-auto h4 download bg-transparent border-0 shadow-none" name = "download" value = "{{ $song['audio_path'] }}" role = "button" data-path = "{{ $song['audio_path'] }}" data-name = "{{ $song['title']['song_title'] }}">
                                        <span role = "button "><i class="fas fa-download"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card shadow-sm mt-4" data-id = "{{ $song['id'] }}" data-index = "{{ $loop->iteration }}">
                            <div class="card-body">
                                <div class="row py-2 px-2">
                                    <div id = "{{ 'song-'.$loop->iteration }}" class="col-md-1 col-12 pt-3 pt-md-0 text-center my-auto h4 song-list-play-btn" role = "button" data-id = "{{ $song['id'] }}" data-name = "{{ $song['title']['song_title'] }}" data-path = "{{ $song['audio_path'] }}">
                                        <span role = "button "><i class="fas fa-play-circle"></i></span>
                                    </div>
                                    <div class="col-md-1 col-12 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Track</small></p>
                                        <p class = "card-text h6">{{ $song['index'] }}</p>
                                    </div>
                                    <div class="col-md-2 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Judul</small></p>
                                        <p class = "card-text h6">{{ $song['title']['song_title'] }}</p>
                                    </div>
                                    <div class="col-md-3 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Penyanyi</small></p>
                                        <p class = "card-text h6">{{ $song['singer']['singer'] }}</p>
                                    </div>
                                    <div class="col-md-2 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Band</small></p>
                                        <p class = "card-text h6">{{ $song['band']['band'] }}</p>
                                    </div>
                                    <div class="col-md-2 col-6 pt-3 pt-md-0 my-auto">
                                        <p class = "card-subtitle text-muted"><small>Pencipta</small></p>
                                        <p class = "card-text h6">{{ $song['arranger']['arranger'] }}</p>
                                    </div>
                                    <button class="col-md-auto col-12 pt-3 pt-md-0 text-center my-auto h4 download bg-transparent border-0 shadow-none" name = "download" value = "{{ $song['audio_path'] }}" role = "button" data-path = "{{ $song['audio_path'] }}" data-name = "{{ $song['title']['song_title'] }}">
                                        <span role = "button "><i class="fas fa-download"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    @endforeach
                </form>
            </div>
            <audio controls preload="metadata" class = "d-none">
                <source src="" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>
        </div>
    </section>
    <section class = "shadow-sm-top" style = "height: 10vh;">
        <div class="container h-100">
            <div class="row h-100" style = "width: 100%">
                <div class="col-4 col-sm-5 col-md-2 justify-content-center d-flex">
                    <div class="row">
                        <div id = "previous-song" class="col align-self-center px-2">
                            <span role = "button"><i class="fas fa-step-backward h5"></i></span>
                        </div>
                        <div class="col align-self-center px-2" id = "play-song">
                            <span role = "button"><i class="far fa-play-circle h1"></i></span>
                        </div>
                        <div id = "next-song" class="col align-self-center px-2">
                            <span role = "button"><i class="fas fa-step-forward h5"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col my-auto">
                    <div class="row">
                        <div id = "current-play-time" class="col-3"><small>-</small></div>
                        <div id = "current-song" class="col text-center">-</div>
                        <div id = "song-duration" class="col-3 text-right"><small>-</small></div>
                    </div>
                    <div class="progress progress-music" style="height: 10px;" role = "button">
                        <div class="progress-bar progress-bar-music" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-2 my-auto d-none d-md-block">
                    <div class="row">
                        <div class="col text-center px-auto px-2">
                            <span role = "button"><i class="fas fa-volume-off"></i></span>
                        </div>
                    </div>
                    <div class="progress progress-volume" style="height: 10px;" role = "button">
                        <div class="progress-bar progress-bar-volume" style="width: 100%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            <!-- <audio controls preload="metadata">
                <source src="" type="audio/mp3">
                Your browser does not support the audio element.
            </audio> -->
        </div>
    </section>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="exampleModalCenterTitle">Deskripsi Album</h5> -->
                <div>
                    <p class = "modal-title card-subtitle text-muted"><small>Deskripsi Album</small></p>
                    <p class = "modal-title card-text h6">{{ $album['title'] }}</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $album['description'] }}
            </div>
        </div>
    </div>
    </div>
@stop

<!-- currently here, see if we can combine album and song page together, so its like spotify.
curretnly trying to use the toast component to act as a player. Need to be ablle to have a position higher than the rest (z-index)

OR MAYBE the only scrollable div is the middle one. So we have 3 section. and the middle section (filled with songs) are the only scrollable div, 
the rest are just static.

After looking at it again, we need to make section with info the same section with songs so that it would scroll down with it. So in the end we
have 2 section, where the top section (album info and song) are the scrollable ones, and the last sectrion (toast player) are not scrollable
and it just stays there. Maybe even with this approach, you dont need to use toast.
-->