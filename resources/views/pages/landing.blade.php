@extends('templates.default')
@section('content')
    <section>
        <div class="jumbotron jumbotron-fluid bg-primary text-center" style = "background-image: url('/storage/web-images/indonesia.svg');background-size: 75%;background-repeat:no-repeat;background-position: center; ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="form-group col dropdown">
                        <h1 class="display-4 text-light font-weight-bold">Cari audio musik sunda <br> dengan mudah</h1>
                        <input id = "search-bar" type="text" class="form-control input-lg mt-md-5 mt-3" aria-label="Text input with dropdown button" data-toggle="dropdown">
                        <div id = "dropdown-search-bar" class="dropdown-menu w-100 overflow-auto" aria-labelledby="search-bar" style = "max-height: 250px">
                            <!-- <h6 role = "button" class="dropdown-header dropdown-item-search-bar"></h6> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class = "container my-3">
        <div id = "landing-search-mobile" class = "d-none">
            <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                    <a id = "pill-album" role="button" class="nav-link active">Album</a>
                </li>
                <li class="nav-item">
                    <a id = "pill-song" role="button" class="nav-link">Lagu</a>
                </li>
            </ul>
            <div class="landing-search-mobile-content">
                <div id = "content-wrapper-album">
                    <div class="row my-3">
                        <div class="col-auto">
                            <img src="https://picsum.photos/200/600" class="card-img-top thumbnail rounded" alt="...">
                        </div>
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-auto">
                            <img src="https://picsum.photos/200/600" class="card-img-top thumbnail rounded" alt="...">
                        </div>
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                </div>
                <div id = "content-wrapper-song">
                    <div class="row my-3">
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div id = "landing-search-desktop" class="row d-none">
            <div class="col">
                <h4>Album</h4>
                <div class = "sideway-card-wrapper">
                    <div role="button" class="row my-3">
                        <div class="col-auto">
                            <img src="https://picsum.photos/200/600" class="card-img-top thumbnail rounded" alt="...">
                        </div>
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                    <div role="button" class="row my-3">
                        <div class="col-auto">
                            <img src="https://picsum.photos/200/600" class="card-img-top thumbnail rounded" alt="...">
                        </div>
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <h4>Lagu</h4>
                <div class = "sideway-card-wrapper">
                    <div role="button" class="row my-3">
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                    <div role="button" class="row my-3">
                        <div class="col">
                            <h5 class = "no-margin">Judul Album</h5>
                            <h6 class = "no-margin text-secondary">Tahun Rilis</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <div id = "album-list-landing" class="card-columns">
            @foreach ($albums as $album)
                <a href="{{ url('album/'.str_replace(' ', '-', $album['title'])) }}">
                    <div class="card album-btn" role = "button">
                        <img src="{{ $album['covers'][0]['cover_path'] }}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="container">
                                <div class="row my-1">
                                    <div class="col-8">
                                        <h5 class = "no-margin">{{ $album['title'] }}</h5>
                                        <h6 class = "no-margin text-secondary">{{ $album['released_date'] }}</h6>
                                    </div>
                                    <div class="col">
                                        <h6 class = "no-margin text-secondary text-right">{{ count($album['songs']) }} Lagu</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @if ($total > 10)
            <div id = "load-button" class="row mt-4">
                <div class="col text-center">
                    <button type="button" class="btn btn-primary btn-lg load-more" value = "10">Load Album</button>
                </div>
            </div>
        @endif
    </section>
@stop