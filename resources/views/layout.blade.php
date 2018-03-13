<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:image" content="{{ asset('images/meta_image.jpeg') }}" />
    <meta property="og:title" content="SPUSM" />
    <meta property="og:description" content="" />

    <title>@yield('title', 'Library System')</title>
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    @yield('internal_stylesheet')
    <style type="text/css">
        html, body {
            font-size: 13px;
            margin:0;
            height:100%;
        }
        #header {
            padding: 5px 0;
            background: #fff;
            border-bottom: 2px solid #5cb85c;
        }
        #header .logo-lg-screen {
            margin-top: -65px;
        }
        #header #main-nav {
            border: none;
            background: none;
        }
        @media (min-width: 1200px) {
            #header #main-nav .main-nav-collapse ul {
                float: right;
            }
        }
        @media (max-width: 1200px) {
            #header #main-nav .main-nav-collapse ul {
                float: right;
            }
        }
        @media (max-width: 1100px) {
            #header #main-nav .main-nav-collapse ul {
                float: right;
            }
        }
        @media (max-width: 770px) {
            #header #main-nav .main-nav-collapse ul {
                float: none;
                margin: 0;
            }
        }
        #header #main-nav .navbar-nav .active > a,
        #header #main-nav .navbar-nav .active > a:focus,
        #header #main-nav .navbar-nav .active > a:hover,
        #header #main-nav .navbar-nav li > a:focus,
        #header #main-nav .navbar-nav li > a:hover {
            background: none;
            color: #5cb85c;
        }
        @media (min-width: 765px) {
            #header #main-nav .navbar-nav .active > a,
            #header #main-nav .navbar-nav .active > a:focus,
            #header #main-nav .navbar-nav .active > a:hover,
            #header #main-nav .navbar-nav li > a:focus,
            #header #main-nav .navbar-nav li > a:hover {
                border-bottom: 3px solid #5cb85c;
                transition: all 0.1s ease-in-out;
                -webkit-transition: all 0.1s ease-in-out;
                -moz-transition: all 0.1s ease-in-out;
                -o-transition: all 0.1s ease-in-out;
            }
        }
        @media (max-width: 765px) {
            #header #main-nav .navbar-nav .active > a,
            #header #main-nav .navbar-nav .active > a:focus,
            #header #main-nav .navbar-nav .active > a:hover,
            #header #main-nav .navbar-nav li > a:focus,
            #header #main-nav .navbar-nav li > a:hover {
                background: white;
                border-left: 3px solid #5cb85c;
                transition: all 0.1s ease-in-out;
                -webkit-transition: all 0.1s ease-in-out;
                -moz-transition: all 0.1s ease-in-out;
                -o-transition: all 0.1s ease-in-out;
            }
        }

        .header-bg {
            padding: 10px;
            background: @primary;
            margin-bottom: 10px;
        }

        #content-header {
            min-height: 80px;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        @media (min-width: 770px) {
            #content-header {
                border-bottom: 1px solid #5cb85c;
            }

            #content-header h4 {
                font-size: 16px;
                font-family: 'lato-regular'!important;

            }
        }
        #content-header h4 {
            color: #fff;
            font-size: 24px;
            font-family: 'lato-regular'!important;

        }
        #content-header .header-bg {
            padding: 10px;
            background: #5cb85c;
            margin-bottom: 10px;
        }

        .search-panel {
            color: #3c763d;
        }

        .self-class {
            font-size: 12px;
            height:100%;
        }

        .pagination > li > a,
        .pagination > li > span {
            color: #3c763d;
        }

        .pagination > .active > a,
        .pagination > .active > a:focus,
        .pagination > .active > a:hover,
        .pagination > .active > span,
        .pagination > .active > span:focus,
        .pagination > .active > span:hover {
            background-color: #3c763d;
            border-color: #3c763d;
        }

        .modal-header {
            padding:9px 15px;
            border-bottom:1px solid #5cb85c;
            background-color: #5cb85c;
            color: white;
            -webkit-border-top-left-radius: 5px;
            -webkit-border-top-right-radius: 5px;
            -moz-border-radius-topleft: 5px;
            -moz-border-radius-topright: 5px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .modal-backdrop
        {
            opacity:0.8 !important;
        }
    </style>
</head>
<body>

@include('nav')

<div id = "content">
	@include('flash::message')
    @yield('content')
</div>

<script>
    var socketio_base = "{{config('services.socketio.url')}}";
</script>

<script src="{{ elixir('js/all.js') }}"></script>

<script type="text/javascript">
    var socket = io(socketio_base);
    @if (Auth::check() and Auth::user()->hasRole('admin'))
        socket.on('book-reserved:App\\Events\\BookReserved', function( message ) {
        $.growl.notice( {
            title : 'Done',
            message	: 'Book '+message.book_reserved+' has been reserved by ' + message.reserved_by +'.'
        });
    });
    @endif
</script>

@include('sweet::alert')
@yield('page_js')
</body>
</html>