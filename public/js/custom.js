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
            console.log(data);
            $('#edit-song-index').val(data['index']);
            $('#edit-song-title').val(data['title']['song_title']);
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
        // if($(window).width() > 576 && $('#landing-search-desktop').hasClass('d-none')) {
        //     $('#landing-search-mobile').addClass('d-none');
        //     $('#landing-search-desktop').removeClass('d-none');
        // } else if($(window).width() <= 576 && $('#landing-search-mobile').hasClass('d-none')) {
        //     $('#landing-search-mobile').removeClass('d-none');
        //     $('#landing-search-desktop').addClass('d-none');
        // }

        // Need to add this logic outside of document ready
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

    $('.img-thumbnail').click((e) => {
        let id = e.target.dataset.id;
        let parent = e.target.parentNode;
        
        if ($('#cover-'+ id).val() == '0') {
            parent.setAttribute('style', 'opacity:0.2 !important');
            $('#cover-'+ id).val(1);
        } else {
            parent.setAttribute('style', 'opacity:1 !important');
            $('#cover-'+ id).val(0);
        }
    })

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
                        $('#picture-list').append('<div class="col-4 col-sm-3 col-md-3"><img role = "button" src="'+ cover['cover_path'] +'" alt="album cover image" class="img-thumbnail"></div>')
                    });
                }

                $('#submit-song').val(data['id']);
            },
            error : function(err) {
                console.log(err);
            }
        });
    });

    $('#search-bar').keyup((e) => {
        let val = e.target.value;

        if (val.length !== 0) {
            $('#album-list-landing').addClass('d-none');

            if($(window).width() > 576) {
                $('#landing-search-mobile').addClass('d-none');
                $('#landing-search-desktop').removeClass('d-none');
            } else if($(window).width() <= 576) {
                $('#landing-search-mobile').removeClass('d-none');
                $('#landing-search-desktop').addClass('d-none');
            }
        } else {
            $('#album-list-landing').removeClass('d-none');
            $('#landing-search-mobile').addClass('d-none');
            $('#landing-search-desktop').addClass('d-none');
        }
    });

    $('.song-list-play-btn').click(function() {
        let name = $(this).data('name');
        let id = $(this).data('id');
        let path = $(this).data('path');
        song = $('audio')[0];

        $('#current-song').html(name);
        $('audio > source').attr('src', path);
        $('#current-play-time > small').html('0:00');
        $('#pause-song > span').html('<i class="far fa-play-circle h1"></i>');
        $('#pause-song').attr('id', 'play-song');
        $('.progress-bar-music').attr('style', 'width:0%');
        song.load();
    });

    $('audio').on("loadedmetadata", function(){
        let rawDuration = Math.round(this.duration);
        let minutes = Math.round(rawDuration / 60);
        let seconds = rawDuration % 60;
        
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
            let minutes = Math.round(currentTime / 60);
            let seconds = Math.round(currentTime % 60);

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
        
        // song.pause();
        song.currentTime = song.duration * pickedTime;
        // song.play();
    });

    $('.progress-volume').click(function(e) {
        let max = $(this).width();
        let pos = e.pageX - $(this).offset().left;
        let pickedVolume = pos / max;

        if (pickedVolume > 100) {
            pickedVolume = 100;
        }

        if (pickedVolume < 0.03) {
            pickedVolume = 0;
        }
        
        song.volume = pickedVolume;

        $('.progress-bar-volume').attr('style', 'width:'+ pickedVolume*100 +'%');

    });
});

$(document).on('click', '#pause-song', function() {
    $('#pause-song > span').html('<i class="far fa-play-circle h1"></i>');
    $('#pause-song').attr('id', 'play-song');
    song.pause();
});
