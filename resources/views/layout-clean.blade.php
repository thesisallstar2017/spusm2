<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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