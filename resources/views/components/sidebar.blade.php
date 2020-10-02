<div class="sticky-top">
    <section class = "bg-light container-fluid pt-3" style = "height: 90vh;">
        <div>
            <!-- <div id = "input-wrapper" class = "dropdown">
                <input id = "search-album" type="text" class="form-control input" aria-label="Text input with dropdown button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="dropdown-menu dropdown-menu-right w-100" aria-labelledby="search-album">
                    <h6 class="dropdown-header">Advanced Settings</h6>
                    <div class="dropdown-divider"></div>
                </div>
            </div> -->
            <div class="album-list row mt-3">
                @if ( $albums )
                    @foreach ( $albums as $album )
                        <div class="col-12 d-flex" data-id = "{{ $album['id'] }}">
                            <span><i class="fas fa-compact-disc mr-2"></i></span>
                            <span role = "button"><p>{{ isset($album['title']) ? $album['title'] : 'Tidak ada nama' }}</p></span>
                        </div>
                    @endforeach
                @else
                    <div class="col text-center text-muted">
                        Tidak ada album
                    </div>
                @endif
            </div>
        </div>
        <!-- <div class="row">
            <div class="col">
                <button type="button" data-toggle="modal" data-target="#create-album" class="btn btn-primary btn btn-block">Tambah Album</button>
            </div>
        </div> -->
    </section>
    <section class = "bg-light container-fluid vh-10" style = "height: 10vh;">
        <div class="row">
            <div class="col">
                <button type="button" data-toggle="modal" data-target="#create-album" class="btn btn-primary btn btn-block">Tambah Album</button>
            </div>
        </div>
    </section>
</div>
