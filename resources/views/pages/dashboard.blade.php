@extends('templates.dashboard')
@section('content')
    <section class = "shadow-sm-left container-fluid" style = "height: 100vh !important">
        <div class="h-100 overflow-auto">
            <div class="row mt-3 mx-2 justify-content-md-center">
                <div class="col-12 col-sm-8">
                    @if (session('status') !== null)
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Sukses!</strong> Album berhasil ditambah.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card">
                        <form action="{{ url('/dashboard/edit-album-cover') }}" method = "post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                Gambar album
                            </div>
                            <div id = "pictures" class="card-body">
                                <div id = "picture-list" class="row">
                                    @if ($albumDetail !== null)
                                        @if ( count($albumDetail['covers']) !== 0 )
                                            @foreach ($albumDetail['covers'] as $albumCover)
                                                <div class="col-4 col-sm-3 col-md-3" data-id = "{{ $albumCover['id'] }}">
                                                    <img role = "button" src="{{ $albumCover['cover_path'] }}" alt="album cover image" class="img-thumbnail" data-id = "{{ $albumCover['id'] }}">
                                                </div>
                                                <input type="hidden" id="{{ 'cover-'.$albumCover['id'] }}" name="{{ 'delete_pictures['.$albumCover['id'].']' }}" value="0">
                                            @endforeach
                                        @else
                                            <div id = "edit-album-cover-placeholder-1" class="col text-center text-muted">
                                                <p class = "mb-0">Tidak ada gambar</p>
                                            </div>
                                        @endif
                                    @else
                                        <div id = "edit-album-cover-placeholder-2" class="col text-center text-muted">
                                            <p class = "mb-0">Tidak ada gambar</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer px-1">
                                <div class="row mx-1">
                                    <div class="col-sm-auto col-5 px-1">
                                        <label for="manipulate-album-covers" class = "btn btn-primary btn-sm btn-block mb-0">Unggah gambar</label>
                                        <input id = "manipulate-album-covers" type="file" name="edit_album_covers[]" class = "d-none" multiple>
                                    </div>
                                    <div class="col-sm-auto col-5 px-1">
                                        <button id = "upload-pictures" class="btn btn-success btn-sm btn-block" name = "id" value = "{{ $albumDetail['id'] }}">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-3 mx-2 justify-content-md-center">
                <div class="col-12 col-sm-8">
                    <div class="card">
                        <form action="{{ url('/dashboard/edit-album-info') }}" method = "post">
                            @method('PUT')
                            @csrf
                            <div class="card-header">
                                Informasi album
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 dropdown">
                                        <label for="inputEmail4">Nama album</label>
                                        <input type="text" class="form-control" id="album-name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" name = "album_name" value = "{{ ($albumDetail['title']) ? $albumDetail['title'] : null }}">
                                        <div class="dropdown-menu dropdown-menu- w-100" aria-labelledby="album-name">
                                            <h6 class="dropdown-header"></h6>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="release-date">Tahun rilis</label>
                                        <input type="text" class="form-control" id="release-date" name = "released_date" value = "{{ ($albumDetail['released_date']) ? $albumDetail['released_date'] : null }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="recording-studio-name">Perusahaan rekaman</label>
                                        <input type="text" class="form-control" id="recording-studio-name" name = "recording_studio" value = "{{ ($albumDetail['recording_company']) ? $albumDetail['recording_company'] : null }}">
                                    </div>
                                </div>
                                <label for="album-description">Deskripsi album</label>
                                <textarea class="form-control" id="album-description" rows = "5" name = "album_description">{{ ($albumDetail['description']) ? $albumDetail['description'] : null }}</textarea>
                            </div>
                            <div class="card-footer">
                                <button id = "edit-album-info" name = "id" value = "{{ $albumDetail['id'] }}" class="btn btn-primary btn-sm">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row my-3 mx-2 justify-content-md-center">
                <div class="col-12 col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            Lagu album
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <form action="{{ url('/dashboard/store-song') }}" method = "post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="card-header" id="headingOne" role = "button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <span class = "text-left">Tambah lagu</span>
                                            <span class = "float-right"><i class="fas fa-chevron-down"></i></span>
                                        </div>
                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-1">
                                                        <label for="song-index">#</label>
                                                        <input type="text" class="form-control" id="song-index" name = "song_index">
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label for="song-title">Judul lagu</label>
                                                        <input type="text" class="form-control" id="song-title" name = "song_title">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="song-writer">Nama pencipta lagu</label>
                                                        <input type="text" class="form-control" id="song-writer" name = "song_writer">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="singer">Nama penyanyi lagu</label>
                                                        <input type="text" class="form-control" id="singer" name = "singer">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="arranger">Nama arranger lagu</label>
                                                        <input type="text" class="form-control" id="arranger" name = "arranger">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="band-leader">Nama pemimpin band</label>
                                                        <input type="text" class="form-control" id="band-leader" name = "band_leader">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="band-name">Nama band lagu</label>
                                                        <input type="text" class="form-control" id="band-name" name = "band_name">
                                                    </div>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="customFile" name = "audio_file">
                                                    <label class="custom-file-label" for="customFile">File audio</label>
                                                </div>
                                                <button type="submit" id = "submit-song" name = "album_id" class="btn btn-primary btn-sm mt-3" value = "{{ $albumDetail['id'] }}">Tambah lagu</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id = "song-list-wrapper">
                                @if ($albumDetail !== null)
                                    @if ( count($albumDetail['songs']) !== 0 )
                                        @foreach ( $albumDetail['songs'] as $song )
                                            <div class="card mt-3">
                                                <div class="card-body">
                                                    <div class="row py-2 px-2">
                                                        <div class="col-md-1 col-12 my-auto">
                                                            <p class = "card-subtitle text-muted"><small>Track</small></p>
                                                            <p class = "card-text h6">{{ $song['index'] }}</p>
                                                        </div>
                                                        <div class="col-md-3 col-6 pt-3 pt-md-0 my-auto">
                                                            <p class = "card-subtitle text-muted"><small>Judul</small></p>
                                                            <p class = "card-text h6">{{ $song['title']['song_title'] }}</p>
                                                        </div>
                                                        <div class="col-md-3 col-6 pt-3 pt-md-0 my-auto">
                                                            <p class = "card-subtitle text-muted"><small>Penyanyi</small></p>
                                                            <p class = "card-text h6">{{ $song['singer']['singer'] }}</p>
                                                        </div>
                                                        <div class="col-md-4 col-6 pt-3 pt-md-0 my-auto">
                                                            <p class = "card-subtitle text-muted"><small>Band</small></p>
                                                            <p class = "card-text h6">{{ $song['band']['band'] }}</p>
                                                        </div>
                                                        <div class="col-md-1 col-12 pt-3 pt-md-0 text-center my-auto h5 song-info" data-id = "{{ $song['id'] }}">
                                                            <span class = "test1" role = "button" data-toggle="modal" data-target="#edit-song" data-id = "{{ $song['id'] }}"><i class="fas fa-ellipsis-v test2" data-id = "{{ $song['id'] }}"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="card mt-3">
                                            <div class="card-body">
                                                <div class="row py-2 px-2">
                                                    <div class="col my-auto text-center text-muted">
                                                        <p class = "mb-0">Tidak ada lagu</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="card mt-3">
                                        <div class="card-body">
                                            <div class="row py-2 px-2">
                                                <div class="col my-auto text-center text-muted">
                                                    <p class = "mb-0">Tidak ada lagu</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="edit-song" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('/dashboard/edit-song') }}" method = "post" enctype=multipart/form-data>
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Lagu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="song-index">#</label>
                                <input type="text" class="form-control" id="edit-song-index" name = "edit_song_index">
                            </div>
                            <div class="form-group col-md-10">
                                <label for="song-title">Judul lagu</label>
                                <input type="text" class="form-control" id="edit-song-title" name = "edit_song_title">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="singer">Nama penyanyi lagu</label>
                                <input type="text" class="form-control" id="edit-singer" name = "edit_singer">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="arranger">Nama arranger lagu</label>
                                <input type="text" class="form-control" id="edit-arranger" name = "edit_arranger">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="band-leader">Nama pemimpin band</label>
                                <input type="text" class="form-control" id="edit-band-leader" name = "edit_band_leader">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="band-name">Nama band lagu</label>
                                <input type="text" class="form-control" id="edit-band-name" name = "edit_band_name">
                            </div>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="edit-audio-file" name = "edit_audio_file">
                            <label class="custom-file-label" for="customFile" id = "edit-audio-file-label">File audio</label>
                        </div>
                    </div>
                    <input type="hidden" id="edit-song-id" name="id" value="">
                    <input type="hidden" id="edit-song-album" name="album_id" value="">
                    <div class="modal-footer">
                        <button formaction="{{ url('/dashboard/delete-song') }}" class="btn btn-danger">Hapus</button>
                        <button class="btn btn-primary">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>
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
                    <!-- <div class="card">
                        <div class="card-header" id="headingOne" role = "button">
                            <span class = "text-left">Tambah lagu</span>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-1">
                                    <label for="song-index">#</label>
                                    <input type="text" class="form-control" id="song-index" name = "song-index">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="song-title">Judul lagu</label>
                                    <input type="text" class="form-control" id="song-title" name = "song-title">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="song-writer">Nama pencipta lagu</label>
                                    <input type="text" class="form-control" id="song-writer" name = "song-writer">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="singer">Nama penyanyi lagu</label>
                                    <input type="text" class="form-control" id="singer" name = "singer">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="arranger">Nama arranger lagu</label>
                                    <input type="text" class="form-control" id="arranger" name = "arranger">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="band-leader">Nama pemimpin band</label>
                                    <input type="text" class="form-control" id="band-leader" name = "band-leader">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="band-name">Nama band lagu</label>
                                    <input type="text" class="form-control" id="band-name" name = "band-name">
                                </div>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name = "audio-file">
                                <label class="custom-file-label" for="customFile">File audio</label>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah album</button>
                </div>
            </form>
        </div>
    </div>
@stop