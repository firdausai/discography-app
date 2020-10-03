@extends('templates.dashboard2')
@section('content')
    <div>
        <div class="swiper-container mt-2">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="card card-max-height">
                        <div class="card-header text-center bg-primary text-white">
                            Total album
                        </div>
                        <div class="card-body ">
                            <h2 class="card-title text-center ">{{ $totalAlbums }}</h2>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="card card-max-height">
                        <div class="card-header text-center bg-info text-white">
                            Total lagu
                        </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{ $totalSongs }}</h2>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="card card-max-height">
                        <div class="card-header text-center bg-success text-white">
                            Total download
                        </div>
                        <div class="card-body">
                            <h2 class="card-title text-center">{{ $totalDownload }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-scrollbar"></div>
        </div>
        <div class = "mt-4 form-inline ">
            <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#create-album">Tambah album</button>
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" class="form-control" id="search-album-list-dashboard" placeholder="Cari album">
            </div>
        </div>
        <div class="album-container mt-4">
            @foreach ($albums as $album)
                <div id = "{{ $loop->index }}" class="card shadow-sm mt-2 testing">
                    <div class="card-body">
                        <div class="row py-2 px-2">
                            <button class="col-auto pt-md-0 text-center my-auto h4 bg-transparent border-0 shadow-none delete-album" data-content = "delete" data-toggle="modal" data-target="#delete-album-modal" data-value="{{ $album['id'] }}">
                                <span role = "button "><i class="fas fa-times text-danger"></i></span>
                            </button>
                            <div class="col my-auto">
                                <p class = "card-subtitle text-muted"><small>Nama album</small></p>
                                <p class = "card-text h6">{{ $album['title'] }}</p>
                            </div>
                            <div class="col my-auto">
                                <p class = "card-subtitle text-muted"><small>Perusahaan rekaman</small></p>
                                <p class = "card-text h6">{{ $album['recording_company'] }}</p>
                            </div>
                            <div class="col my-auto d-none d-sm-block">
                                <p class = "card-subtitle text-muted"><small>Tahun rilis</small></p>
                                <p class = "card-text h6">{{ $album['released_date'] }}</p>
                            </div>
                            <a href = "{{ url('dashboard/album/'.str_replace(' ', '-', $album['title'])) }}" class="col-2 text-center my-auto h4" role = "button">
                                <span role = "button "><i class="fas fa-arrow-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal fade" id="delete-album-modal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ url('dashboard/delete-album') }}" method = "POST">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Album</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus album ini?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id = "delete-album-btn" name = "id" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal fade" id="create-album" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ url('/dashboard/store-album') }}" method = "post" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card ">
                        <div class="card-header">
                            Gambar album
                        </div>
                        <div class="card-body">
                            <div id = "add-album-covers-preview" class="row">
                                <div id = "add-album-cover-placeholder" class="col text-center text-muted">
                                    <p class = "mb-0">Tidak ada gambar</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer px-1">
                            <div class="row mx-1">
                                <div class="col-sm-auto col-2 px-1">
                                    <button id = "remove-pictures" type="button" class="btn btn-secondary btn-sm btn-block" data-toggle="popover" data-placement="bottom" title="Hapus gambar" title="Popover title" data-content='test' disabled><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                                <div class="col-sm-auto col-5 px-1">
                                    <!-- <button id = "add-pictures" for = "file-input" type="button" class="btn btn-primary btn-sm btn-block">Unggah gambar</button> -->
                                    <label for="add-album-covers" class = "btn btn-primary btn-sm btn-block mb-0">Unggah gambar</label>
                                    <input id = "add-album-covers" type="file" name="album-covers[]" class = "d-none" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card my-3">
                        <div class="card-header">
                            Informasi album
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6 dropdown">
                                    <label for="inputEmail4">Nama album</label>
                                    <input type="text" class="form-control" id="album-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name = "album-name">
                                    <div class="dropdown-menu dropdown-menu- w-100" aria-labelledby="album-name">
                                        <h6 class="dropdown-header"></h6>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="release-date">Tahun rilis</label>
                                    <input type="text" class="form-control" id="release-date" name = "released-date">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="recording-studio-name">Perusahaan rekaman</label>
                                    <input type="text" class="form-control" id="recording-studio-name" name = "recording-studio">
                                </div>
                            </div>
                            <label for="album-description">Deskripsi album</label>
                            <textarea class="form-control" id="album-description" rows = "5" name = "album-description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah album</button>
                </div>
            </form>
        </div>
    </div>
@stop