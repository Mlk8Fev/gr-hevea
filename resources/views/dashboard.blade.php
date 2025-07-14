<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WowDash - Dashboard Laravel</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/favicon.png') }}" sizes="16x16">
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/bootstrap.min.css') }}">
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/apexcharts.css') }}">
    <!-- Data Table css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/dataTables.min.css') }}">
    <!-- Text Editor css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/editor.quill.snow.css') }}">
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/flatpickr.min.css') }}">
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/full-calendar.css') }}">
    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/jquery-jvectormap-2.0.5.css') }}">
    <!-- Popup css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/magnific-popup.css') }}">
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/slick.css') }}">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/prism.css') }}">
    <!-- file upload css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('wowdash/css/lib/audioplayer.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('wowdash/css/style.css') }}">
</head>
<body>
@include('partials.sidebar')

<main class="dashboard-main">
    <div class="navbar-header">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <button type="button" class="sidebar-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                        <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                    </button>
                    <button type="button" class="sidebar-mobile-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                    </button>
                    <form class="navbar-search">
                        <input type="text" name="search" placeholder="Rechercher">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                    
                    <div class="dropdown">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                                </div>
                                <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">{{ count($notifications) }}</span>
                            </div>
                            
                           <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
                            @foreach($notifications as $notification)
                            <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"> 
                                    <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                        <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xxl"></iconify-icon>
                                    </span> 
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">{{ $notification['title'] }}</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-200-px">{{ $notification['message'] }}</p>
                                    </div>
                                </div>
                                <span class="text-sm text-secondary-light flex-shrink-0">{{ $notification['time'] }}</span>
                            </a>
                            @endforeach
                           </div>
                            <div class="text-center py-12 px-16"> 
                                <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">Voir toutes les notifications</a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                            <img src="{{ $user['avatar'] }}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm">
                            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ $user['name'] }}</h6>
                                    <span class="text-secondary-light fw-medium text-sm">{{ $user['role'] }}</span>
                                </div>
                                <button type="button" class="hover-text-danger">
                                    <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon> 
                                </button>
                            </div>
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#"> 
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> Mon Profil</a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="#"> 
                                    <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon> Paramètres</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3 w-100 border-0 bg-transparent"> 
                                        <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Analytics</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Analytics</li>
            </ul>
        </div>
        
        <div class="row gy-4">
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-body p-20">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="trail-bg h-100 text-center d-flex flex-column justify-content-between align-items-center p-16 radius-8">
                                    <h6 class="text-white text-xl">Mise à jour du plan</h6>
                                    <div class="">
                                        <p class="text-white">Votre essai gratuit expire dans 7 jours</p>
                                        <a href="#" class="btn py-8 rounded-pill w-100 bg-gradient-blue-warning text-sm">Mettre à jour</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-3">
                                    @foreach(array_keys($stats) as $statKey)
                                    <div class="col-sm-6 col-xs-6">
                                        <div class="radius-8 h-100 text-center p-20 bg-{{ $stats[$statKey]['color'] }}-light">
                                            <span class="w-44-px h-44-px radius-8 d-inline-flex justify-content-center align-items-center text-xl mb-12 bg-{{ $stats[$statKey]['color'] }}-200 border border-{{ $stats[$statKey]['color'] }}-400 text-{{ $stats[$statKey]['color'] }}-600">
                                                <i class="{{ $stats[$statKey]['icon'] }}"></i>
                                            </span>
                                            <span class="text-neutral-700 d-block">{{ $stats[$statKey]['label'] }}</span>
                                            <h6 class="mb-0 mt-4">{{ $stats[$statKey]['count'] }}</h6>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="card h-100">
                    <div class="card-body p-24 mb-8">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Statistiques des revenus</h6>
                            <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                                <option>Annuel</option>
                                <option>Mensuel</option>
                                <option>Hebdomadaire</option>
                                <option>Aujourd'hui</option>
                            </select>
                        </div>
                        <ul class="d-flex flex-wrap align-items-center justify-content-center my-3 gap-24">
                            <li class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-8-px h-8-px rounded-pill bg-primary-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Profit </span>
                                </div>
                                <div class="d-flex align-items-center gap-8">
                                    <h6 class="mb-0">$26,201</h6>
                                    <span class="text-success-600 d-flex align-items-center gap-1 text-sm fw-bolder">
                                        10%
                                        <i class="ri-arrow-up-s-fill d-flex"></i>
                                    </span>
                                </div>
                            </li>
                            <li class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="w-8-px h-8-px rounded-pill bg-success-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Revenus</span>
                                </div>
                                <div class="d-flex align-items-center gap-8">
                                    <h6 class="mb-0">$18,201</h6>
                                    <span class="text-success-600 d-flex align-items-center gap-1 text-sm fw-bolder">
                                        8%
                                        <i class="ri-arrow-up-s-fill d-flex"></i>
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- jQuery library js -->
<script src="{{ asset('wowdash/js/lib/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap js -->
<script src="{{ asset('wowdash/js/lib/bootstrap.bundle.min.js') }}"></script>
<!-- Apex Chart js -->
<script src="{{ asset('wowdash/js/lib/apexcharts.min.js') }}"></script>
<!-- Data Table js -->
<script src="{{ asset('wowdash/js/lib/dataTables.min.js') }}"></script>
<!-- Iconify Font js -->
<script src="{{ asset('wowdash/js/lib/iconify-icon.min.js') }}"></script>
<!-- jQuery UI js -->
<script src="{{ asset('wowdash/js/lib/jquery-ui.min.js') }}"></script>
<!-- Vector Map js -->
<script src="{{ asset('wowdash/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('wowdash/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- Popup js -->
<script src="{{ asset('wowdash/js/lib/magnifc-popup.min.js') }}"></script>
<!-- Slick Slider js -->
<script src="{{ asset('wowdash/js/lib/slick.min.js') }}"></script>
<!-- prism js -->
<script src="{{ asset('wowdash/js/lib/prism.js') }}"></script>
<!-- file upload js -->
<script src="{{ asset('wowdash/js/lib/file-upload.js') }}"></script>
<!-- audioplayer -->
<script src="{{ asset('wowdash/js/lib/audioplayer.js') }}"></script>
<!-- main js -->
<script src="{{ asset('wowdash/js/app.js') }}"></script>

</body>
</html>