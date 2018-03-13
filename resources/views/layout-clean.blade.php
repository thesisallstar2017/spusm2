<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:image" content="{{ public_path('images/meta_image.jpeg') }}" />
    <meta property="og:title" content="SPUSM" />
    <meta property="og:description" content="Saint Paul University San Miguel Bulacan Library System" />
    <title>@yield('title', 'Library System')</title>
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    @yield('internal_stylesheet')
</head>
<body style="padding: 0;">

<div class="container-fluid">
	@include('flash::message')
    @yield('content')
</div>

<script>
    var socketio_base = "{{config('services.socketio.url')}}";
</script>

<script src="{{ elixir('js/all.js') }}"></script>
@include('sweet::alert')
@yield('page_js')
</body>
</html>