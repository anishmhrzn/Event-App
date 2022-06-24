<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body class="antialiased">
        <nav class="navbar navbar-dark bg-dark mb-3">
            <div class="container-fluid">
                <span class="text-white">Event App</span>
            </div>
        </nav>
        <div id="app">
            <router-view></router-view>
        </div>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
