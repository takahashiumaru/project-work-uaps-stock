<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr"
      data-theme="theme-default"
      data-assets-path="{{ asset('template/assets/') }}/"
      data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>Lupa Password</title>
    <meta name="description" content="" />
    <link rel="icon" href="{{ asset('storage/aps_mini.png') }}" sizes="48x48" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('template/assets/js/config.js') }}"></script>

    {{-- Sinkron theme dari localStorage sebelum first paint (sama dengan layout.admin) --}}
    <script>
        (function () {
            const STORAGE_KEY = 'pwus_theme';
            const saved = localStorage.getItem(STORAGE_KEY) || 'light';
            const root = document.documentElement;

            root.setAttribute('data-theme', saved);
            root.classList.toggle('dark-style', saved === 'dark');
            root.classList.toggle('light-style', saved !== 'dark');
            root.style.colorScheme = saved === 'dark' ? 'dark' : 'light';
        })();
    </script>

    <style>
        body{
            background: radial-gradient(circle at top, #eef2ff 0, #f9fafb 45%, #f3f4f6 100%);
        }
        .auth-card{
            border-radius: 20px !important;
            box-shadow: 0 16px 45px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(148, 163, 184, 0.25);
            background-color: #ffffff;
        }
        .auth-logo{
            border-radius: 16px;
            max-height: 120px;
            width: auto;
        }
        h4.page-title{
            font-weight: 700;
            letter-spacing: .02em;
            color: #0f172a;
        }
        .page-subtitle{
            font-size: .9rem;
            color: #6b7280;
        }
        .form-label{
            font-size: .85rem;
            color: #374151;
        }
        .form-control{
            border-radius: 12px;
        }
        .btn-primary{
            border-radius: 999px;
            font-weight: 600;
            letter-spacing: .02em;
        }

        /* ======== NIGHT MODE (forgot password) ======== */
        html[data-theme="dark"] body{
            background: radial-gradient(circle at top, #020617 0, #020617 40%, #020617 100%);
            color: #e5e7eb;
        }
        html[data-theme="dark"] .auth-card{
            background-color: #020617;
            border-color: #1f2937;
            box-shadow: 0 18px 45px rgba(0,0,0,0.65);
        }
        html[data-theme="dark"] h4.page-title{
            color: #e5e7eb;
        }
        html[data-theme="dark"] .page-subtitle{
            color: #9ca3af;
        }
        html[data-theme="dark"] .form-label{
            color: #e5e7eb;
        }
        html[data-theme="dark"] .form-control{
            background-color: #020617;
            border-color: #334155;
            color: #e5e7eb;
        }
        html[data-theme="dark"] .form-control::placeholder{
            color: #6b7280;
        }
        html[data-theme="dark"] .btn-primary{
            box-shadow: 0 12px 30px rgba(15,23,42,0.8);
        }
        html[data-theme="dark"] .alert-danger{
            background-color: rgba(248,113,113,0.12);
            border-color: rgba(248,113,113,0.4);
            color: #fecaca;
        }
        html[data-theme="dark"] .alert-success{
            background-color: rgba(34,197,94,0.10);
            border-color: rgba(34,197,94,0.4);
            color: #bbf7d0;
        }
        html[data-theme="dark"] .text-muted{
            color: #9ca3af !important;
        }
        html[data-theme="dark"] a.small{
            color: #93c5fd;
        }
        html[data-theme="dark"] a.small:hover{
            color: #bfdbfe;
        }
    </style>
  </head>
  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <div class="card shadow-sm border-0 auth-card">
            <div class="card-body p-4 p-md-5">
              <div class="app-brand justify-content-center mb-3">
                <a href="javascript:void(0);" class="app-brand-link gap-2 align-items-center">
                  <img src="{{ asset('storage/aps_light.png') }}" alt="Logo" class="auth-logo" id="forgotLogo">
                </a>
              </div>
              <h4 class="mb-1 text-center page-title">Lupa Password</h4>
              <p class="mb-4 text-center page-subtitle">
                Masukkan NIP Anda untuk menerima OTP reset password melalui email terdaftar.
              </p>

              @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              @if(session('otp_sent'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  OTP telah dikirim ke email Anda.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              <form method="POST"
                    action="{{ session('otp_sent') ? route('forgot.password.verify') : route('forgot.password.send') }}">
                @csrf
                <div class="mb-3">
                  <label for="id" class="form-label fw-semibold">NIP</label>
                  <input type="text"
                         class="form-control @error('id') is-invalid @enderror"
                         name="id"
                         value="{{ old('id') }}"
                         required autofocus {{ session('otp_sent') ? 'readonly' : '' }}>
                  @error('id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                @if(session('otp_sent'))
                  <div class="mb-3">
                    <label for="otp" class="form-label fw-semibold">OTP</label>
                    <input type="text"
                           class="form-control @error('otp') is-invalid @enderror"
                           name="otp" required>
                    @error('otp')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <button type="submit" class="btn btn-primary d-grid w-100" style="border-radius:999px;">
                    Verifikasi OTP
                  </button>
                @else
                  <button type="submit" class="btn btn-primary d-grid w-100" style="border-radius:999px;">
                    Kirim OTP
                  </button>
                @endif
              </form>

              <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small">
                  &larr; Kembali ke Login
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('template/assets/js/main.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
      // Sinkron logo forgot password dengan theme (light/dark)
      (function () {
          const STORAGE_KEY = 'pwus_theme';
          const logo = document.getElementById('forgotLogo');
          if (!logo) return;

          function applyLogo(theme) {
              const base = '{{ asset('storage') }}';
              logo.src = theme === 'dark'
                  ? base + '/aps_dark.png'
                  : base + '/aps_light.png';
          }

          const saved = localStorage.getItem(STORAGE_KEY) || 'light';
          applyLogo(saved);
      })();
    </script>
  </body>
</html>