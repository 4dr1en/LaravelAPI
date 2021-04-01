<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link rel="stylesheet" href="{{ asset('/css/boostrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    @yield('css')
    <title>@yield('title')</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">Accueil</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/list">Liste</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/add">Ajout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <footer></footer>
    <!-- JavaScript Bundle with Popper -->
    <script src="{{ asset('/js/boostrap.min.js') }}"></script>
</body>
</html>
