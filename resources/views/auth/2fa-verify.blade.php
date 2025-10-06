<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vérification 2FA - FPH-CI</title>
    <link rel="icon" type="image/png" href="{{ asset('wowdash/images/fph-ci.png') }}" sizes="16x16">
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
    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none" style="background-image: url('{{ asset('wowdash/images/Couv.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <a href="{{ url('/') }}" class="mb-40 max-w-290-px">
                        <img src="{{ asset('wowdash/images/fph-ci.png') }}" alt="">
                    </a>
                    <h4 class="mb-16 fw-bold" style="color: #447748;">Vérification 2FA</h4>
                    <p class="mb-32 text-secondary-light text-lg">Entrez le code à 6 chiffres reçu par email</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mb-16">
                        <iconify-icon icon="solar:check-circle-outline" class="me-2"></iconify-icon>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-16">
                        <iconify-icon icon="solar:danger-circle-outline" class="me-2"></iconify-icon>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="text-center mb-24">
                    <iconify-icon icon="solar:shield-check-outline" style="font-size: 48px; color: #447748;"></iconify-icon>
                    <p class="text-secondary-light mt-16 mb-0">Un code à 6 chiffres a été envoyé à : <strong>{{ auth()->user()->email }}</strong></p>
                </div>

                <form action="{{ route('2fa.email.verify') }}" method="POST">
                    @csrf
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="solar:lock-keyhole-outline"></iconify-icon>
                        </span>
                        <input type="text" 
                               name="code" 
                               class="form-control h-56-px bg-neutral-50 radius-12 text-center" 
                               placeholder="123456" 
                               maxlength="6"
                               pattern="[0-9]{6}"
                               autofocus
                               required
                               style="letter-spacing: 8px; font-size: 18px; font-weight: bold;">
                    </div>

                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                        Vérifier et accéder
                    </button>
                </form>

                <div class="text-center mt-24">
                    <small class="text-secondary-light">
                        ⏱️ Le code expire dans 5 minutes
                    </small>
                </div>

                <div class="text-center mt-16">
                    <form action="{{ route('2fa.email.send-verify') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <iconify-icon icon="solar:refresh-outline" class="icon"></iconify-icon>
                            Renvoyer le code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

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
