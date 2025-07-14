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
<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('wowdash/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('wowdash/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('wowdash/images/logo-icon.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('dashboard') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Analytics</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-menu-group-title">Application</li>
            @foreach($navigation as $item)
                @if($item['type'] == 'title')
                    <li class="sidebar-menu-group-title">{{ $item['title'] }}</li>
                @else
                    <li>
                        <a href="{{ $item['url'] }}">
                            <i class="{{ $item['icon'] }} menu-icon"></i>
                            <span>{{ $item['title'] }}</span>
                            @if(isset($item['badge']))
                                <span class="badge bg-danger ms-auto">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside>

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
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" href="javascript:void(0)"> 
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Déconnexion</a>
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
                                    @foreach(['users', 'reports', 'orders', 'revenue'] as $statKey)
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
                                    <span class="w-8-px h-8-px rounded-pill bg-lilac-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Perte </span>
                                </div>
                                <div class="d-flex align-items-center gap-8">
                                    <h6 class="mb-0">$18,120</h6>
                                    <span class="text-danger-600 d-flex align-items-center gap-1 text-sm fw-bolder">
                                        10%
                                        <i class="ri-arrow-down-s-fill d-flex"></i>
                                    </span>
                                </div>
                            </li>
                        </ul>
                        <div id="revenueChart" class="apexcharts-tooltip-style-1"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xxl-8">
                <div class="card h-100">
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
                    <h6 class="text-lg fw-semibold mb-0">Activité récente</h6>
                    <a href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                    Voir tout
                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive scroll-sm">
                    <table class="table bordered-table mb-0 rounded-0 border-0">
                        <thead>
                            <tr>
                                <th scope="col" class="bg-transparent rounded-0">Utilisateur</th>
                                <th scope="col" class="bg-transparent rounded-0">Action</th>
                                <th scope="col" class="bg-transparent rounded-0">Temps</th>
                                <th scope="col" class="bg-transparent rounded-0">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivity as $activity)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-primary-100 flex-shrink-0 me-12">
                                            <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="text-md mb-0">{{ $activity['user'] }}</h6>
                                            <span class="text-sm text-secondary-light fw-medium">{{ $activity['action'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $activity['action'] }}</td>
                                <td>{{ $activity['time'] }}</td>
                                <td> <span class="bg-success-focus text-success-main px-10 py-4 radius-8 fw-medium text-sm">Terminé</span> </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>
            
            <div class="col-xxl-4">
                <div class="card h-100">
                    <div class="card-body p-24">
                      <div class="d-flex align-items-start flex-column gap-2">
                        <h6 class="mb-2 fw-bold text-lg">Tâches en cours</h6>
                        <span class="text-secondary-light">{{ count($tasks) }} tâches actives</span>
                    </div>
          
                      <div class="d-flex flex-column gap-32 mt-32">
                        @foreach($tasks as $task)
                        <div class="d-flex align-items-center justify-content-between gap-3">
                          <div class="d-flex align-items-center gap-3">
                            <div class="w-40-px h-40-px rounded-circle d-flex justify-content-center align-items-center bg-{{ $task['priority'] }}-100 flex-shrink-0">
                                <i class="ri-task-line text-{{ $task['priority'] }}"></i>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="text-md mb-0 fw-semibold">{{ $task['name'] }}</h6>
                              <span class="text-sm text-secondary-light fw-normal">{{ $task['assignee'] }}</span>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-8">
                              <span class="text-secondary-light text-md fw-medium">{{ $task['progress'] }}%</span>
                              <span class="text-success-600 text-md fw-medium">{{ $task['deadline'] }}</span>
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="d-footer">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <p class="mb-0">© 2024 WowDash. Tous droits réservés.</p>
        </div>
        <div class="col-auto">
            <p class="mb-0">Créé avec <span class="text-primary-600">Laravel</span></p>
        </div>
    </div>
</footer>
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

<script>
    // ===================== Revenue Chart Start =============================== 
    function createChartTwo(chartId, color1, color2) {
        var options = {
            series: [{
                name: 'series1',
                data: [6, 20, 15, 48, 28, 55, 28, 52, 25, 32, 15, 25]
            }, {
                name: 'series2',
                data: [0, 8, 4, 36, 16, 42, 16, 40, 12, 24, 4, 12]
            }],
            legend: {
                show: false 
            },
            chart: {
                type: 'area',
                width: '100%',
                height: 150,
                toolbar: {
                    show: false
                },
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: [color1, color2],
                lineCap: 'round'
            },
            grid: {
                show: true,
                borderColor: '#D1D5DB',
                strokeDashArray: 1,
                position: 'back',
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                row: {
                    colors: undefined,
                    opacity: 0.5
                },
                column: {
                    colors: undefined,
                    opacity: 0.5
                },
                padding: {
                    top: -20,
                    right: 0,
                    bottom: -10,
                    left: 0
                },
            },
            fill: {
                type: 'gradient',
                colors: [color1, color2],
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: [undefined, `${color2}00`],
                    inverseColors: false,
                    opacityFrom: [0.4, 0.6],
                    opacityTo: [0.3, 0.3],
                    stops: [0, 100],
                },
            },
            markers: {
                colors: [color1, color2],
                strokeWidth: 2,
                size: 0,
                hover: {
                    size: 8
                }
            },
            
            xaxis: {
                labels: {
                    show: false
                },
                categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                tooltip: {
                    enabled: false
                },
                labels: {
                    formatter: function (value) {
                        return value;
                    },
                    style: {
                        fontSize: "14px"
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                    return "$" + value + "k";
                    },
                    style: {
                    fontSize: "14px"
                    }
                },
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
        chart.render();
    }

    createChartTwo('revenueChart', '#CD20F9', '#6593FF');
    // ===================== Revenue Chart End =============================== 
</script>

</body>
</html>