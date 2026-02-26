<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr"
      data-theme="theme-default"
      data-assets-path="{{ asset('template/assets/') }}/"
      data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>Ganti Password</title>
    <meta name="description" content="" />
    <link rel="icon" href="{{ asset('storage/aps_mini.png') }}" sizes="48x48" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}" />
    <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('template/assets/js/config.js') }}"></script>
    <style>
        body{
            background: radial-gradient(circle at top, #eef2ff 0, #f9fafb 45%, #f3f4f6 100%);
        }
        .auth-card{
            border-radius: 20px !important;
            box-shadow: 0 16px 45px rgba(15, 23, 42, 0.12);
            border: 1px solid rgba(148, 163, 184, 0.25);
        }
        .auth-logo{
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.15);
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
                  <img src="{{ asset('storage/aps.jpeg') }}" alt="Logo" height="72"
                       class="auth-logo">
                </a>
              </div>
              <h4 class="mb-1 text-center page-title">Ganti Password</h4>
              <p class="mb-4 text-center page-subtitle">
                  Masukkan dan konfirmasi password baru Anda.
              </p>

              @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
              @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  @foreach($errors->all() as $error)
                    {{ $error }}<br>
                  @endforeach
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              <form method="POST" action="/forgot-password/change">
                @csrf
                <div class="mb-3">
                  <label for="password" class="form-label fw-semibold">Password Baru</label>
                  <input type="password" class="form-control" name="password" required autofocus>
                </div>
                <div class="mb-3">
                  <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                  <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100" style="border-radius:999px;">
                  Simpan Password
                </button>
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
  </body>
</html>