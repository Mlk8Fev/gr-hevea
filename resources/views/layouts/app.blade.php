<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration')</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
    
    <!-- WowDash CSS -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
    
    @stack('styles')
</head>
<body>
@include('partials.sidebar', ['navigation' => $navigation])

<main class="dashboard-main">
    @include('partials.navbar-header')
    
    @yield('content')
</main>

<!-- jQuery library js -->
<script src="{{ asset('wowdash/js/lib/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap js -->
<script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
<!-- Iconify Font js -->
<script src="{{ asset('wowdash/js/lib/iconify-icon.min.js') }}"></script>
<!-- main js -->
<script src="{{ asset('wowdash/js/app.js') }}"></script>

@stack('scripts')
</body>
</html> 