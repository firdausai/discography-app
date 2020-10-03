if($(window).width() > 992) {
    $('#album-info-wrapper').addClass('height-35-vh');
} else if($(window).width() <= 992) {
    $('#album-info-wrapper').removeClass('height-35-vh');
}

$('#content-wrapper-song').addClass('d-none');

var song;

$(document).on('click', '.song-info', function() {
    var id = $(this).data('id');
    $.ajax({
        type:'GET',
        url:'/dashboard/get-song',
        data: { id : id },
        success : function(data) {
            $('#edit-song-index').val(data['index']);
            $('#edit-song-title').val(data['title']['song_title']);
            $('#edit-song-writer').val((data['song_writer'] !== null) ? data['song_writer']['song_writer'] : ' ');
            $('#edit-singer').val(data['singer']['singer']);
            $('#edit-arranger').val(data['arranger']['arranger']);
            $('#edit-band-leader').val(data['band_leader']['band_leader']);
            $('#edit-band-name').val(data['band']['band']);
            $('#edit-audio-file-label').text(data['title']['song_title']);
            $('#edit-song-id').val(data['id']);
            $('#edit-song-album').val(data['album_id']);
            
        },
        error : function(err) {
            console.log(err);
        }
    });
});

$(document).ready(function() {
    $(window).resize(() => {
        if($(window).width() > 992 && !$('#album-info-wrapper').hasClass('height-35-vh')) {
            $('#album-info-wrapper').addClass('height-35-vh');
        } else if($(window).width() <= 992 && $('#album-info-wrapper').hasClass('height-35-vh')) {
            $('#album-info-wrapper').removeClass('height-35-vh');
        }
    });

    $('#pill-album').click(() => {
        $('#pill-album').addClass('active');
        $('#pill-song').removeClass('active');
        $('#content-wrapper-song').addClass('d-none');
        $('#content-wrapper-album').removeClass('d-none');
    });

    $('#pill-song').click(() => {
        $('#pill-album').removeClass('active');
        $('#pill-song').addClass('active');
        $('#content-wrapper-song').removeClass('d-none');
        $('#content-wrapper-album').addClass('d-none');
    });

    $(() => {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $(() => {
        $('[data-toggle="popover"]').popover({
            html : true,
            title:function(){
                return $('.popover_title').html();
            },
            content:function(){
                return $('#delete-pictures').html();
                
            }
        })
    });

    $('.toast').toast({
        autohide: false,
    });

    $('.toast').toast('show');

    // $('.img-thumbnail').on('click', function() {
    //     let id = $(this).data('id');

    //     if ($('#cover-'+ id).val() == '0') {
    //         $(this).parent().css('opacity', 0.2);
    //         $('#cover-'+ id).val(1);
    //     } else {
    //         $(this).parent().css('opacity', 1);
    //         $('#cover-'+ id).val(0);
    //     }
    // });

    $("#manipulate-album-covers").change(function() {
        $('#edit-album-cover-placeholder-1').remove();
        $('#edit-album-cover-placeholder-2').remove();
        if (this.files) {
            Array.from(this.files).forEach((file, index) => {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#picture-list').append('<div id = "'+ index +'" class="col-4 col-sm-3 col-md-3"><img role = "button" src="'+ e.target.result +'" alt="album cover image" class="img-thumbnail"></div>');
                }
                
                reader.readAsDataURL(file);
            });
        }
    });
      
    $("#add-album-covers").change(function() {
        $('#add-album-cover-placeholder').remove();
        if (this.files) {
            Array.from(this.files).forEach((file, index) => {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#add-album-covers-preview').append('<div id = "'+ index +'" class="col-4 col-sm-3 col-md-3"><img role = "button" src="'+ e.target.result +'" alt="album cover image" class="img-thumbnail"></div>');
                }
                
                reader.readAsDataURL(file);
            });
        }
    });

    $('.album-list').click((e) => {
        var id = e.target.parentNode.parentNode.dataset.id;
        $.ajax({
            type:'GET',
            url:'/dashboard/get-album',
            data: { id : id },
            success : function(data) {
                
                $('#album-name').val(data['title']);
                $('#album-id-hidden').val(data['id']);
                $('#released-date').val(data['released_date']);
                $('#recording-studio-name').val(data['recording_company']);
                $('#album-description').val(data['description']);
                $('#song-list-wrapper').empty();
                $('#edit-album-info').val(data['id']);
                $('#upload-pictures').val(data['id']);

                if (data['songs'].length === 0) {
                    $('#song-list-wrapper').append('<div class="card mt-3"><div class="card-body"><div class="row py-2 px-2"><div class="col my-auto text-center text-muted"><p class = "mb-0">Tidak ada lagu</p></div></div></div></div>');
                } else {
                    data['songs'].forEach((song) => {
                        $('#song-list-wrapper').append('<div class="card mt-3"><div class="card-body"><div class="row py-2 px-2"><div class="col-md-1 col-12 my-auto"><p class = "card-subtitle text-muted"><small>Track</small></p><p class = "card-text h6">'+ song['index'] +'</p></div><div class="col-md-3 col-6 pt-3 pt-md-0 my-auto"><p class = "card-subtitle text-muted"><small>Judul</small></p><p class = "card-text h6">'+ song['title']['song_title'] +'</p></div><div class="col-md-3 col-6 pt-3 pt-md-0 my-auto"><p class = "card-subtitle text-muted"><small>Penyanyi</small></p><p class = "card-text h6">'+ song['singer']['singer'] +'</p></div><div class="col-md-4 col-6 pt-3 pt-md-0 my-auto"><p class = "card-subtitle text-muted"><small>Band</small></p><p class = "card-text h6">'+ song['band']['band']+'</p></div><div class="col-md-1 col-12 pt-3 pt-md-0 text-center my-auto h5 song-info" data-id = '+ song['id'] +'><span class = "test1" role = "button" data-toggle="modal" data-target="#edit-song" data-id = '+ song['id'] +'><i class="fas fa-ellipsis-v test2" data-id = '+ song['id'] +'></i></span></div></div></div></div>');
                    });
                }

                $('#picture-list').empty();

                if (data['covers'].length === 0) {
                    $('#picture-list').append('<div class="col text-center text-muted"><p class = "mb-0">Tidak ada gambar</p></div>')
                } else {
                    data['covers'].forEach((cover) => {
                        $('#picture-list').append('<div class="col-4 col-sm-3 col-md-3" data-id = '+ cover['id'] +'><img role = "button" src="'+ cover['cover_path'] +'" alt="album cover image" class="img-thumbnail" data-id = '+ cover['id'] +'></div>');
                        $('#picture-list').append('<input type="hidden" id="cover-'+ cover['id'] +'" name="delete_pictures['+ cover['id'] +']" value="0">');
                    });
                }

                $('#submit-song').val(data['id']);
                $('#dropdown-album-name').html('<h6 role = "button" class="dropdown-header dropdown-header">'+ data['title'] +'</h6>');
            },
            error : function(err) {
                console.log(err);
            }
        });
    });

    var currentIndex;
    var totalSongs = $('.card').siblings();

    $('.song-list-play-btn').click(function() {
        let name = $(this).data('name');
        currentIndex = $(this).attr('id');
        // console.log(currentSongDom);
        let path = $(this).data('path');
        console.log(path);
        // currentIndex = $(this).closest('.card').data('index');
        song = $('audio')[0];
        
        $('.card').css('border-color', '#dee2e6');
        $('#current-song').html(name);
        $('audio > source').attr('src', path);
        $('#current-play-time > small').html('0:00');
        $('#pause-song > span').html('<i class="far fa-play-circle h1"></i>');
        $('#pause-song').attr('id', 'play-song');
        $('.progress-bar-music').attr('style', 'width:0%');
        $(this).closest('.card').css('border-color', '#006CD1');

        $('#play-song > span').html('<i class="far fa-pause-circle h1"></i>');
        $('#play-song').attr('id', 'pause-song');
        song.volume = pickedVolume;
        song.load();
        song.play();

        song.ontimeupdate = function() {
            let rawDuration = this.duration;
            let currentTime = song.currentTime;
            let percentage = currentTime * 100 / rawDuration;
            let minutes = Math.floor(currentTime / 60);
            let seconds = Math.floor(currentTime % 60);

            if (seconds < 10) {
                seconds = '0' + seconds;
            }

            $('#current-play-time > small').html(minutes + ':' + seconds)
            $('.progress-bar-music').attr('style', 'width:'+ percentage +'%');
        };
    });

    $('#previous-song').click(function() {
        let id = parseInt(currentIndex.split('-')[1]);
        if (parseInt(id) != 1) {
            id = parseInt(id) - 1
            let name = $('#song-'+ id.toString()).data('name');
            currentIndex = $('#song-'+ id.toString()).attr('id');
            let path = $('#song-'+ id.toString()).data('path');
            console.log(path);
            song = $('audio')[0];
        
            $('.card').css('border-color', '#dee2e6');
            $('#current-song').html(name);
            $('audio > source').attr('src', path);
            $('#current-play-time > small').html('0:00');
            $('#pause-song > span').html('<i class="far fa-play-circle h1"></i>');
            $('#pause-song').attr('id', 'play-song');
            $('.progress-bar-music').attr('style', 'width:0%');
            $('#song-'+ id.toString()).closest('.card').css('border-color', '#006CD1');

            $('#play-song > span').html('<i class="far fa-pause-circle h1"></i>');
            $('#play-song').attr('id', 'pause-song');
            song.volume = pickedVolume;
            song.load();
            song.play();

            song.ontimeupdate = function() {
                let rawDuration = this.duration;
                let currentTime = song.currentTime;
                let percentage = currentTime * 100 / rawDuration;
                let minutes = Math.floor(currentTime / 60);
                let seconds = Math.floor(currentTime % 60);

                if (seconds < 10) {
                    seconds = '0' + seconds;
                }

                $('#current-play-time > small').html(minutes + ':' + seconds)
                $('.progress-bar-music').attr('style', 'width:'+ percentage +'%');
            };
        }
    });

    var maxSong = $('.card').length;
    
    $('#next-song').click(function() {
        let id = parseInt(currentIndex.split('-')[1]);
        if (parseInt(id) != parseInt(maxSong)) {
            id = parseInt(id) + 1;
            let name = $('#song-'+ id.toString()).data('name');
            currentIndex = $('#song-'+ id.toString()).attr('id');
            let path = $('#song-'+ id.toString()).data('path');
            console.log(path);
            song = $('audio')[0];
        
            $('.card').css('border-color', '#dee2e6');
            $('#current-song').html(name);
            $('audio > source').attr('src', path);
            $('#current-play-time > small').html('0:00');
            $('#pause-song > span').html('<i class="far fa-play-circle h1"></i>');
            $('#pause-song').attr('id', 'play-song');
            $('.progress-bar-music').attr('style', 'width:0%');
            $('#song-'+ id.toString()).closest('.card').css('border-color', '#006CD1');

            $('#play-song > span').html('<i class="far fa-pause-circle h1"></i>');
            $('#play-song').attr('id', 'pause-song');
            song.volume = pickedVolume;
            song.load();
            song.play();

            song.ontimeupdate = function() {
                let rawDuration = this.duration;
                let currentTime = song.currentTime;
                let percentage = currentTime * 100 / rawDuration;
                let minutes = Math.floor(currentTime / 60);
                let seconds = Math.floor(currentTime % 60);

                if (seconds < 10) {
                    seconds = '0' + seconds;
                }

                $('#current-play-time > small').html(minutes + ':' + seconds)
                $('.progress-bar-music').attr('style', 'width:'+ percentage +'%');
            };
        }
    });

    $('audio').on("loadedmetadata", function(){
        let rawDuration = Math.round(this.duration);
        let minutes = Math.round(rawDuration / 60);
        let seconds = rawDuration % 60;
        console.log(this.duration);
        if (seconds < 10) {
            seconds = '0' + seconds;
        }

        $('#song-duration > small').html(minutes + ':' + seconds)
    });

    $('#play-song').on('click', function() {
        $('#play-song > span').html('<i class="far fa-pause-circle h1"></i>');
        $('#play-song').attr('id', 'pause-song');
        song.play();

        song.ontimeupdate = function() {
            let rawDuration = this.duration;
            let currentTime = song.currentTime;
            
            let percentage = currentTime * 100 / rawDuration;
            let minutes = Math.floor(currentTime / 60);
            let seconds = Math.floor(currentTime % 60);
            
            if (seconds < 10) {
                seconds = '0' + seconds;
            }

            $('#current-play-time > small').html(minutes + ':' + seconds)
            $('.progress-bar-music').attr('style', 'width:'+ percentage +'%');
        };
    });

    $('.progress-music').click(function(e) {
        let max = $(this).width();
        let pos = e.pageX - $(this).offset().left;
        let pickedTime = pos / max;

        if (pickedTime > 100) {
            pickedTime = 100;
        }
        
        song.currentTime = song.duration * pickedTime;
    });

    let pickedVolume = 1;

    $('.progress-volume').click(function(e) {
        let max = $(this).width();
        let pos = e.pageX - $(this).offset().left;
        pickedVolume = pos / max;

        if (pickedVolume > 100) {
            pickedVolume = 100;
        }

        if (pickedVolume < 0.03) {
            pickedVolume = 0;
        }
        
        if (song) {
            song.volume = pickedVolume;
        }

        $('.progress-bar-volume').attr('style', 'width:'+ pickedVolume*100 +'%');

    });

    var typingLandingSearch;
    var doneTypingLandingSearch = 2000;

    $('#search-bar').keyup(function() {
        clearTimeout(typingLandingSearch);
        if ($(this).val()) {
            typingLandingSearch = setTimeout(getMatchingGlobalSearch, doneTypingLandingSearch);
            $('#dropdown-search-bar').empty();
            $('#dropdown-search-bar').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        } else {
            $('#landing-search-mobile').addClass('d-none');
            $('#album-list-landing').removeClass('d-none');
            $('#dropdown-search-bar').empty();
        }
    });

    function getMatchingGlobalSearch() {
        $.ajax({
            type:'GET',
            url:'/query/get-matching-global-search',
            data: { name : $('#search-bar').val() },
            success : function(data) {
                // console.log(data);
                let len = Object.keys(data).length;
                let empty = 0;
                let category;

                $('#dropdown-search-bar').empty();
                for (const key in data) {
                    if (data[key].length === 0) {
                        console.log('here');
                        empty += 1;
                    } else {
                        if (key === 'albumName') {
                            category = 'Nama album';
                        } else if (key === 'arranger') {
                            category = 'Arranger';
                        } else if (key === 'band') {
                            category = 'Band';
                        } else if (key === 'bandLeader') {
                            category = 'Pemimpin band';
                        } else if (key === 'recordingCompany') {
                            category = 'Perusahaan rekaman';
                        } else if (key === 'singer') {
                            category = 'Penyanyi';
                        } else if (key === 'songTitle') {
                            category = 'Judul lagu';
                        } else if (key === 'songWriter') {
                            category = 'Penulis lagu';
                        } else if (key === 'releasedDate') {
                            category = 'Tahun rilis';
                        }

                        data[key].sort();
                        data[key].forEach(match => {
                            $('#dropdown-search-bar').append('<a role = "button" class="dropdown-item dropdown-item-search-bar" data-category = "'+ key +'" data-match = "'+ match +'">'+ match +' <small class = "text-muted">'+ category +'</small></a>');
                        })
                    }
                }
                console.log(empty);
                if (empty === len) {
                    $('#dropdown-search-bar').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                }
                $('#search-bar').dropdown('show');
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerAlbumName;
    var doneTypingIntervalAlbumName = 2000;

    $('#album-name').keyup(function() {
        clearTimeout(typingTimerAlbumName);
        if ($(this).val()) {
            typingTimerAlbumName = setTimeout(getMatchingAlbumName, doneTypingIntervalAlbumName);
            $('#dropdown-album-name').empty();
            $('#dropdown-album-name').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingAlbumName() {
        $.ajax({
            type:'GET',
            url:'/query/get-album-name',
            data: { name : $('#album-name').val() },
            success : function(data) {
                $('#dropdown-album-name').empty();
                if (data.length === 0) {
                    $('#dropdown-album-name').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#album-name').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-album-name').append('<h6 role = "button" class="dropdown-header dropdown-item-album-name">'+ name.title +'</h6>');
                        $('#album-name').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerRecordingCompany;
    var doneTypingIntervalRecordingCompany = 2000;

    $('#recording-studio-name').keyup(function() {
        clearTimeout(typingTimerRecordingCompany);
        if ($(this).val()) {
            typingTimerRecordingCompany = setTimeout(getMatchingRecordingCompany, doneTypingIntervalRecordingCompany);
            $('#dropdown-recording-studio').empty();
            $('#dropdown-recording-studio').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingRecordingCompany() {
        $.ajax({
            type:'GET',
            url:'/query/get-recording-company',
            data: { name : $('#recording-studio-name').val() },
            success : function(data) {
                $('#dropdown-recording-studio').empty();
                
                if (data.length === 0) {
                    $('#dropdown-recording-studio').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#recording-studio-name').dropdown('hide');
                } else {
                    data.forEach(company => {
                        $('#dropdown-recording-studio').append('<h6 role = "button" class="dropdown-header dropdown-item-recording-studio">'+ company.recording_company +'</h6>');
                        $('#recording-studio-name').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerSongTitle;
    var doneTypingIntervalSongTitle = 2000;

    $('#song-title').keyup(function() {
        clearTimeout(typingTimerSongTitle);
        if ($(this).val()) {
            typingTimerSongTitle = setTimeout(getMatchingSongTitle, doneTypingIntervalSongTitle);
            $('#dropdown-song-title').empty();
            $('#dropdown-song-title').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingSongTitle() {
        $.ajax({
            type:'GET',
            url:'/query/get-song-title',
            data: { name : $('#song-title').val() },
            success : function(data) {
                $('#dropdown-song-title').empty();
                if (data.length === 0) {
                    $('#dropdown-song-title').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#song-title').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-song-title').append('<h6 role = "button" class="dropdown-header dropdown-item-song-title">'+ name.song_title +'</h6>');
                        $('#song-title').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerEditSongTitle;
    var doneTypingIntervalEditSongTitle = 2000;

    $('#edit-song-title').keyup(function() {
        clearTimeout(typingTimerEditSongTitle);
        if ($(this).val()) {
            typingTimerEditSongTitle = setTimeout(getMatchingEditSongTitle, doneTypingIntervalEditSongTitle);
            $('#dropdown-edit-song-title').empty();
            $('#dropdown-edit-song-title').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingEditSongTitle() {
        $.ajax({
            type:'GET',
            url:'/query/get-song-title',
            data: { name : $('#edit-song-title').val() },
            success : function(data) {
                $('#dropdown-edit-song-title').empty();
                if (data.length === 0) {
                    $('#dropdown-song-title').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#song-title').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-edit-song-title').append('<h6 role = "button" class="dropdown-header dropdown-item-edit-song-title">'+ name.song_title +'</h6>');
                        $('#edit-song-title').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerSongWriter;
    var doneTypingIntervalSongWriter = 2000;

    $('#song-writer').keyup(function() {
        clearTimeout(typingTimerSongWriter);
        if ($(this).val()) {
            typingTimerSongWriter = setTimeout(getMatchingSongWriter, doneTypingIntervalSongWriter);
            $('#dropdown-song-writer').empty();
            $('#dropdown-song-writer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingSongWriter() {
        $.ajax({
            type:'GET',
            url:'/query/get-song-writer',
            data: { name : $('#song-writer').val() },
            success : function(data) {
                $('#dropdown-song-writer').empty();
                if (data.length === 0) {
                    $('#dropdown-song-writer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#song-writer').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-song-writer').append('<h6 role = "button" class="dropdown-header dropdown-item-song-writer">'+ name.song_writer +'</h6>');
                        $('#song-writer').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerEditSongWriter;
    var doneTypingIntervalEditSongWriter = 2000;

    $('#edit-song-writer').keyup(function() {
        clearTimeout(typingTimerEditSongWriter);
        if ($(this).val()) {
            typingTimerEditSongWriter = setTimeout(getMatchingEditSongWriter, doneTypingIntervalEditSongWriter);
            $('#dropdown-edit-song-writer').empty();
            $('#dropdown-edit-song-writer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingEditSongWriter() {
        $.ajax({
            type:'GET',
            url:'/query/get-song-writer',
            data: { name : $('#edit-song-writer').val() },
            success : function(data) {
                $('#dropdown-edit-song-writer').empty();
                if (data.length === 0) {
                    $('#dropdown-edit-song-writer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#song-writer').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-edit-song-writer').append('<h6 role = "button" class="dropdown-header dropdown-item-edit-song-writer">'+ name.song_writer +'</h6>');
                        $('#edit-song-writer').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerSinger;
    var doneTypingIntervalSinger = 2000;

    $('#singer').keyup(function() {
        clearTimeout(typingTimerSinger);
        if ($(this).val()) {
            typingTimerSinger = setTimeout(getMatchingSinger, doneTypingIntervalSinger);
            $('#dropdown-singer').empty();
            $('#dropdown-singer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingSinger() {
        $.ajax({
            type:'GET',
            url:'/query/get-singer',
            data: { name : $('#singer').val() },
            success : function(data) {
                $('#dropdown-singer').empty();
                if (data.length === 0) {
                    $('#dropdown-singer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#singer').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-singer').append('<h6 role = "button" class="dropdown-header dropdown-item-singer">'+ name.singer +'</h6>');
                        $('#singer').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerEditSinger;
    var doneTypingIntervalEditSinger = 2000;

    $('#edit-singer').keyup(function() {
        clearTimeout(typingTimerEditSinger);
        if ($(this).val()) {
            typingTimerEditSinger = setTimeout(getMatchingEditSinger, doneTypingIntervalEditSinger);
            $('#dropdown-edit-singer').empty();
            $('#dropdown-edit-singer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingEditSinger() {
        $.ajax({
            type:'GET',
            url:'/query/get-singer',
            data: { name : $('#edit-singer').val() },
            success : function(data) {
                $('#dropdown-edit-singer').empty();
                if (data.length === 0) {
                    $('#dropdown-edit-singer').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#edit-singer').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-edit-singer').append('<h6 role = "button" class="dropdown-header dropdown-item-edit-singer">'+ name.singer +'</h6>');
                        $('#edit-singer').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerArranger;
    var doneTypingIntervalArranger = 2000;

    $('#arranger').keyup(function() {
        clearTimeout(typingTimerArranger);
        if ($(this).val()) {
            typingTimerArranger = setTimeout(getMatchingArranger, doneTypingIntervalArranger);
            $('#dropdown-arranger').empty();
            $('#dropdown-arranger').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingArranger() {
        $.ajax({
            type:'GET',
            url:'/query/get-arranger',
            data: { name : $('#arranger').val() },
            success : function(data) {
                $('#dropdown-arranger').empty();
                if (data.length === 0) {
                    $('#dropdown-arranger').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#arranger').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-arranger').append('<h6 role = "button" class="dropdown-header dropdown-item-arranger">'+ name.arranger +'</h6>');
                        $('#arranger').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerEditArranger;
    var doneTypingIntervalEditArranger = 2000;

    $('#edit-arranger').keyup(function() {
        clearTimeout(typingTimerEditArranger);
        if ($(this).val()) {
            typingTimerEditArranger = setTimeout(getMatchingEditArranger, doneTypingIntervalEditArranger);
            $('#dropdown-edit-arranger').empty();
            $('#dropdown-edit-arranger').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingEditArranger() {
        $.ajax({
            type:'GET',
            url:'/query/get-arranger',
            data: { name : $('#edit-arranger').val() },
            success : function(data) {
                $('#dropdown-edit-arranger').empty();
                if (data.length === 0) {
                    $('#dropdown-edit-arranger').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#edit-arranger').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-edit-arranger').append('<h6 role = "button" class="dropdown-header dropdown-item-edit-arranger">'+ name.arranger +'</h6>');
                        $('#edit-arranger').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerBandLeader;
    var doneTypingIntervalBandLeader = 2000;

    $('#band-leader').keyup(function() {
        clearTimeout(typingTimerBandLeader);
        if ($(this).val()) {
            typingTimerBandLeader = setTimeout(getMatchingBandLeader, doneTypingIntervalBandLeader);
            $('#dropdown-band-leader').empty();
            $('#dropdown-band-leader').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingBandLeader() {
        $.ajax({
            type:'GET',
            url:'/query/get-band-leader',
            data: { name : $('#band-leader').val() },
            success : function(data) {
                $('#dropdown-band-leader').empty();
                if (data.length === 0) {
                    $('#dropdown-band-leader').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#band-leader').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-band-leader').append('<h6 role = "button" class="dropdown-header dropdown-item-band-leader">'+ name.band_leader +'</h6>');
                        $('#band-leader').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerEditBandLeader;
    var doneTypingIntervalEditBandLeader = 2000;

    $('#edit-band-leader').keyup(function() {
        clearTimeout(typingTimerEditBandLeader);
        if ($(this).val()) {
            typingTimerEditBandLeader = setTimeout(getMatchingEditBandLeader, doneTypingIntervalEditBandLeader);
            $('#dropdown-edit-band-leader').empty();
            $('#dropdown-edit-band-leader').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingEditBandLeader() {
        $.ajax({
            type:'GET',
            url:'/query/get-band-leader',
            data: { name : $('#edit-band-leader').val() },
            success : function(data) {
                $('#dropdown-edit-band-leader').empty();
                if (data.length === 0) {
                    $('#dropdown-edit-band-leader').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#band-leader').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-edit-band-leader').append('<h6 role = "button" class="dropdown-header dropdown-item-edit-band-leader">'+ name.band_leader +'</h6>');
                        $('#edit-band-leader').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerBand;
    var doneTypingIntervalBand = 2000;

    $('#band-name').keyup(function() {
        clearTimeout(typingTimerBand);
        if ($(this).val()) {
            typingTimerBand = setTimeout(getMatchingBand, doneTypingIntervalBand);
            $('#dropdown-band-name').empty();
            $('#dropdown-band-name').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingBand() {
        $.ajax({
            type:'GET',
            url:'/query/get-band-name',
            data: { name : $('#band-name').val() },
            success : function(data) {
                $('#dropdown-band-name').empty();
                if (data.length === 0) {
                    $('#dropdown-band-name').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#band-name').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-band-name').append('<h6 role = "button" class="dropdown-header dropdown-item-band-name">'+ name.band +'</h6>');
                        $('#band-name').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    var typingTimerEditBand;
    var doneTypingIntervalEditBand = 2000;

    $('#edit-band-name').keyup(function() {
        clearTimeout(typingTimerEditBand);
        if ($(this).val()) {
            typingTimerEditBand = setTimeout(getMatchingEditBand, doneTypingIntervalEditBand);
            $('#dropdown-band-name').empty();
            $('#dropdown-band-name').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Sedang mencari...</em></h6>');
        }
    });

    function getMatchingEditBand() {
        $.ajax({
            type:'GET',
            url:'/query/get-band-name',
            data: { name : $('#edit-band-name').val() },
            success : function(data) {
                $('#dropdown-edit-band-name').empty();
                if (data.length === 0) {
                    $('#dropdown-edit-band-name').append('<h6 role = "button" class="dropdown-header dropdown-header"><em>Tidak ada yang match</em></h6>');
                    $('#edit-band-name').dropdown('hide');
                } else {
                    data.forEach(name => {
                        $('#dropdown-edit-band-name').append('<h6 role = "button" class="dropdown-header dropdown-item-edit-band-name">'+ name.band +'</h6>');
                        $('#edit-band-name').dropdown('show');
                    });
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    }

    // var currentContent = 'covers';

    $('.nav-item').click(function() {
        if (!$(this).children().hasClass('active')) {
            $('.nav-item').children().removeClass('active');
            $(this).children().toggleClass('active');

            if ($(this).data('content') === 'covers') {
                $('#target-covers').removeClass('d-none');
                $('#target-informations').addClass('d-none');
                $('#target-songs').addClass('d-none');
                currentContent = $(this).data('content');
            } else if ($(this).data('content') === 'informations') {
                $('#target-covers').addClass('d-none');
                $('#target-informations').removeClass('d-none');
                $('#target-songs').addClass('d-none');
                currentContent = $(this).data('content');
            } else if ($(this).data('content') === 'songs') {
                $('#target-covers').addClass('d-none');
                $('#target-informations').addClass('d-none');
                $('#target-songs').removeClass('d-none');
                currentContent = $(this).data('content');
            }
        }

        // if (($(this).data('content') === 'delete')) {
        //     $('#delete-album-btn').val($('#album-id-hidden').val());
        //     console.log($('#album-id-hidden').val());
        // }
    });

    $('.delete-album').click(function() {
        $('#delete-album-btn').val($(this).data('value'));
    });

    $('.download').click(function(e) {
        $('#filename').val($(this).data('name'));
        $('#song-id').val($(this).data('id'));
    });

    $('.load-more').click(function() {
        $.ajax({
            type:'GET',
            url:'/get-more-album',
            data: { row : parseInt($(this).val()) },
            success : function(data) {
                // console.log(data);
                let url = $('meta[name=url]').attr("content");
                
                data[0].forEach(album => {
                    $('#album-list-landing').append('<a href="'+ url + '/' + album['title'].replace(' ', '-') +'"><div class="card album-btn" role = "button"><img src="'+ album['covers'][0]['cover_path'] +'" class="card-img-top" alt="..."><div class="card-body"><div class="container"><div class="row my-1"><div class="col-8"><h5 class = "no-margin">'+ album['title'] +'</h5><h6 class = "no-margin text-secondary">'+ album['released_date'] +'</h6></div><div class="col"><h6 class = "no-margin text-secondary text-right">'+ album['songs'].length +' Lagu</h6></div></div></div></div></div></a>');
                });

                if (!$('.load-more').val() + data[0].length < data[1]) {
                    $('.load-more').addClass('d-none');
                }
            },
            error : function(err) {
                console.log(err);
            }
        });
    });

    $('#edit-audio-file').change(function() {
        $('#edit-audio-file-label').text($(this)[0].files[0].name);
    });

    $('#add-song').change(function() {
        $('#add-song-label').text($(this)[0].files[0].name);
    });

    $('#search-album-list-dashboard').keyup(function() {
        let search = $(this).val();
        let status = 0;
        $('.testing').each(function(idx, obj) {
            $(obj).find('.card-text').each(function(i, obj) {
                if ($(obj).text().indexOf(search) >= 0) {
                    if (status == 0) {
                        $('#'+idx).removeClass('d-none');
                        status = 1;
                    }
                } else {
                    if (status == 0) {
                        $('#'+idx).addClass('d-none');
                    }
                }
            });
            status = 0;
        });
    });
});

$(document).on('click', '.dropdown-item-album-name', function() {
    $('#album-name').val($(this).html());
});

$(document).on('click', '.dropdown-item-recording-studio', function() {
    $('#recording-studio-name').val($(this).html());
});

$(document).on('click', '.dropdown-item-song-title', function() {
    $('#song-title').val($(this).html());
});

$(document).on('click', '.dropdown-item-song-writer', function() {
    $('#song-writer').val($(this).html());
});

$(document).on('click', '.dropdown-item-singer', function() {
    $('#singer').val($(this).html());
});

$(document).on('click', '.dropdown-item-arranger', function() {
    $('#arranger').val($(this).html());
});

$(document).on('click', '.dropdown-item-band-leader', function() {
    $('#band-leader').val($(this).html());
});

$(document).on('click', '.dropdown-item-band-name', function() {
    $('#band-name').val($(this).html());
});

$(document).on('click', '.dropdown-item-edit-song-title', function() {
    $('#edit-song-title').val($(this).html());
});

$(document).on('click', '.dropdown-item-edit-song-writer', function() {
    $('#edit-song-writer').val($(this).html());
});

$(document).on('click', '.dropdown-item-edit-singer', function() {
    $('#edit-singer').val($(this).html());
});

$(document).on('click', '.dropdown-item-edit-arranger', function() {
    $('#edit-arranger').val($(this).html());
});

$(document).on('click', '.dropdown-item-edit-band-leader', function() {
    $('#edit-band-leader').val($(this).html());
});

$(document).on('click', '.dropdown-item-edit-band-name', function() {
    $('#edit-band-name').val($(this).html());
});

$(document).on('click', '.dropdown-item-search-bar', function() {
    $('#search-bar').val($(this).data('match'));

    $('#landing-search-mobile').removeClass('d-none');
    $('#album-list-landing').addClass('d-none');
    
    $.ajax({
        type:'GET',
        url:'/query/get-clicked-category',
        data: { match : $(this).data('match'), category : $(this).data('category') },
        success : function(data) {
            $('#content-wrapper-album').empty();
            $('#content-wrapper-song').empty();
            let baseUrl = $('meta[name=url]').attr("content");
            let albumUrl;
            data.forEach(album => {
                albumUrl = album['title'].replace(' ', '-');
                $('#content-wrapper-album').append('<a href = "'+ baseUrl +'/'+ albumUrl +'"><div role = "button" class="row my-3"><div class="col-auto"><img src="'+ album['covers'][0]['cover_path'] +'" class="card-img-top thumbnail rounded" alt="..."></div><div class="col"><h5 class = "no-margin">'+ album['title'] +'</h5><h6 class = "no-margin text-secondary">'+ album['released_date'] +'</h6></div></div></a>');
                
                if (!Array.isArray(album['songs'])) {
                    const arr = Object.keys(album['songs']).map(i => album['songs'][i])
                    album['songs'] = arr;
                }

                album['songs'].forEach(song => {
                    $('#content-wrapper-song').append('<a href = "'+ baseUrl +'/'+ albumUrl +'"><div role = "button" class="row my-3" data-test=""><div class="col"><h5 class = "no-margin">'+ song['title']['song_title'] +'</h5><h6 class = "no-margin text-secondary">'+ song['singer']['singer'] +'</h6></div></div></a>')
                })
            });
        },
        error : function(err) {
            console.log(err);
        }
    });
});

// $('#search-bar').keyup((e) => {
    //     let val = e.target.value;

    //     if (val.length !== 0) {
    //         $('#album-list-landing').addClass('d-none');

    //         if($(window).width() > 576) {
    //             $('#landing-search-mobile').addClass('d-none');
    //             $('#landing-search-desktop').removeClass('d-none');
    //         } else if($(window).width() <= 576) {
    //             $('#landing-search-mobile').removeClass('d-none');
    //             $('#landing-search-desktop').addClass('d-none');
    //         }
    //     } else {
    //         $('#album-list-landing').removeClass('d-none');
    //         $('#landing-search-mobile').addClass('d-none');
    //         $('#landing-search-desktop').addClass('d-none');
    //     }
    // });

$(document).on('click', '#pause-song', function() {
    $('#pause-song > span').html('<i class="far fa-play-circle h1"></i>');
    $('#pause-song').attr('id', 'play-song');
    song.pause();
});

var totalDelete = 0;

$(document).on('click', '.img-thumbnail', function() {
    let id = $(this).data('id');

    if ($('#cover-'+ id).val() == '0' || $('#cover-'+ id).val() == 0) {
        $(this).parent().css('opacity', 0.2);
        $('#cover-'+ id).val(1);
        totalDelete += 1;
    } else {
        $(this).parent().css('opacity', 1);
        $('#cover-'+ id).val(0);
        totalDelete -= 1;
    }

    if (totalDelete == 0) {
        $('#delete-indicator').addClass('d-none');
    } else {
        $('#delete-indicator').removeClass('d-none');
        $('#delete-text').text('Hapus ' + totalDelete);
    }
});
