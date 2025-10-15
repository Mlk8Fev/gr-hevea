<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FPH-CI Systeme de Gestion et de Tracabilité de la Graine d'Hevea</title>
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
  <style>
#rate-limit-alert {
    border-left: 4px solid #f59e0b;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border: 1px solid #f59e0b;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
}

#countdown-timer {
    font-family: 'Courier New', monospace;
    font-size: 1.2em;
    background: rgba(245, 158, 11, 0.1);
    padding: 4px 8px;
    border-radius: 6px;
    border: 1px solid #f59e0b;
}

.form-disabled {
    pointer-events: none;
    opacity: 0.5;
    transition: opacity 0.3s ease;
}
</style>
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
                <h4 class="mb-16 fw-bold" style="color: #447748;">FPH-CI Systeme de Gestion et de Tracabilité de la Graine d'Hevea</h4>
                <h4 class="mb-12">Connexion à votre compte</h4>
                <p class="mb-32 text-secondary-light text-lg">Bienvenue ! Veuillez entrer vos identifiants</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger mb-16">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- Alerte de limitation des tentatives --}}
            @if(session('error') && session('retry_after'))
                <div class="alert alert-warning mb-16" id="rate-limit-alert">
                    <div class="d-flex align-items-center">
                        <i class="ri-eye-line me-2 text-warning"></i>
                        <div>
                            <strong>{{ session('error') }}</strong>
                            <div class="mt-2">
                                <span class="text-sm">Temps restant : </span>
                                <span id="countdown-timer" class="fw-bold text-warning"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const retryAfter = {{ session('retry_after') }};
                    const countdownElement = document.getElementById('countdown-timer');
                    const alertElement = document.getElementById('rate-limit-alert');
                    const form = document.querySelector('form');
                    const submitButton = form.querySelector('button[type="submit"]');
                    
                    // Désactiver le formulaire
                    form.style.pointerEvents = 'none';
                    form.style.opacity = '0.5';
                    submitButton.disabled = true;
                    
                    let remainingTime = retryAfter; // Variable locale pour le décompte
                    
                    function updateCountdown() {
                        const minutes = Math.floor(remainingTime / 60);
                        const seconds = remainingTime % 60;
                        
                        countdownElement.textContent = 
                            (minutes < 10 ? '0' + minutes : minutes) + ':' + 
                            (seconds < 10 ? '0' + seconds : seconds);
                        
                        if (remainingTime <= 0) {
                            // Réactiver le formulaire
                            form.style.pointerEvents = 'auto';
                            form.style.opacity = '1';
                            submitButton.disabled = false;
                            alertElement.style.display = 'none';
                            
                            // Recharger la page pour nettoyer la session
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                            return;
                        }
                        
                        remainingTime--; // Décrémenter le temps restant
                        setTimeout(updateCountdown, 1000);
                    }
                    
                    updateCountdown();
                });
                </script>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <i class="ri-eye-line"></i>
                    </span>
                    <input type="text" name="username" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Nom d'utilisateur" value="{{ old('username') }}" required>
                </div>
                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <i class="ri-eye-line"></i>
                        </span> 
                        <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Mot de passe" required>
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
                </div>

                <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Se connecter</button>
                
            </form>
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

<script>
      // ================== Password Show Hide Js Start ==========
      function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on('click', function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    // Call the function
    initializePasswordToggle('.toggle-password');
  // ========================= Password Show Hide Js End ===========================
</script>

</body>
</html> 
