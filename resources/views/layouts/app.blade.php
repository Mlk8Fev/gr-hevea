<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration')</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <!-- Ajoute ici d'autres liens CSS si besoin -->
</head>
<body>
    @include('partials.sidebar')
    <main>
        @yield('content')
    </main>
    <script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('wowdash/js/app.js') }}"></script>
    <!-- Ajoute ici d'autres scripts JS si besoin -->
</body>
</html> 