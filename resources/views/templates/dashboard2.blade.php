<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <![endif]-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
        @include('components.head')
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <nav class="navbar navbar-expand-md navbar-light bg-light sticky-top bg-secondary shadow-sm">
            <!-- <a role = "button" class = "h5 my-auto text-dark mr-3 d-block d-sm-none" data-toggle="collapse" data-target="#test"><i class="fas fa-bars"></i></a> -->
            <a class="navbar-brand" href="#">Dashboard</a>
            <form class="form-inline ml-auto d-block d-sm-none">
                <button class="btn btn-outline-danger my-2 my-sm-0 float-right" type="submit"><i class="fas fa-power-off align-middle mr-1"></i> Logout</button>
            </form>
            <div class="collapse navbar-collapse" id="test">
                <ul class="navbar-nav mr-auto d-block d-sm-none">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Daftar album</a>
                    </li>
                </ul>
            </div>
            <form class="form-inline ml-auto d-none d-sm-block">
                <button class="btn btn-outline-danger my-2 my-sm-0 float-right" type="submit"><i class="fas fa-power-off align-middle mr-1"></i> Logout</button>
            </form>
        </nav>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <!-- <div class="col-sm-3 d-none d-sm-block bg-light">
                    @include('components.sidebar2')  
                </div> -->
                <div class="col-sm-9">
                    @yield('content')
                </div>
            </div>
        
        </div>
        <!-- <div class="container-fluid">
        </div> -->
        <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/custom.js') }}"></script>
        <script>
            var swiper = new Swiper('.swiper-container', {
                slidesPerView: 1,
                spaceBetween: 15,
                scrollbar: {
                    el: '.swiper-scrollbar',
                    hide: false,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 1,
                        spaceBetween: 15,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 15,
                    },
                }
            });
        </script>
    </body>
</html>