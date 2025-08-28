<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration')</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
</head>
<body>
    @include('partials.sidebar')
    <main>
        @yield('content')
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts personnalisés -->
    <script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('wowdash/js/app.js') }}"></script>
</body>
</html> 