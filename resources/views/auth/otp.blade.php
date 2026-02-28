{{-- filepath: /Applications/XAMPP/xamppfiles/htdocs/project-work-uaps-stock/resources/views/auth/otp.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr"
      data-theme="theme-default"
      data-assets-path="{{ asset('template/assets/') }}/"
      data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>

  <title>Verifikasi OTP</title>

  <link rel="icon" href="{{ asset('storage/aps_mini.png') }}" sizes="48x48" type="image/png">
  <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('template/assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('template/assets/vendor/css/pages/page-auth.css') }}" />

  <script src="{{ asset('template/assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('template/assets/js/config.js') }}"></script>

  <style>
    /* ...existing code... */

    .otp-wrap{
      display:flex;
      justify-content:center;
      gap:10px;
      margin: 10px 0 2px;
    }
    .otp-box{
      width: 46px;
      height: 54px;
      border-radius: 14px;
      border: 1px solid rgba(148,163,184,.55);
      background: #fff;
      text-align:center;
      font-weight: 900;
      font-size: 1.25rem;
      color: #0f172a;
      outline: none;
      transition: box-shadow .15s ease, border-color .15s ease, transform .15s ease;
    }
    .otp-box:focus{
      border-color: rgba(86,97,248,.9);
      box-shadow: 0 0 0 .2rem rgba(86,97,248,.18);
      transform: translateY(-1px);
    }

    @media (max-width: 420px){
      .otp-wrap{ gap: 8px; }
      .otp-box{ width: 42px; height: 52px; border-radius: 12px; }
    }

    .auth-actions {
      display: flex;
      gap: .75rem;
      align-items: center;
      justify-content: space-between;
      margin-top: 1rem;
      position: relative;
      z-index: 5;
    }
    .auth-actions .btn {
      border-radius: 12px;
    }
    .auth-divider {
      margin: 1rem 0 0.75rem;
      border-top: 1px solid rgba(148,163,184,.35);
    }
    .resend-hint {
      font-size: .85rem;
      color: #64748b;
      margin-top: .25rem;
      text-align: center;
    }

    /* make primary action more "curve" */
    .btn-curve{
      border-radius: 999px !important;
      padding-top: .7rem;
      padding-bottom: .7rem;
      font-weight: 700;
    }

    /* resend as link */
    .resend-link{
      font-weight: 700;
      text-decoration: none;
    }
    .resend-link:hover{ text-decoration: underline; }
    .resend-link.is-disabled{
      pointer-events: none;
      opacity: .55;
      text-decoration: none;
      cursor: default;
    }

    .resend-hint {
      font-size: .85rem;
      color: #64748b;
      margin-top: .35rem;
      text-align: center;
    }

    /* card curve like login */
    .auth-card{
      border-radius: 22px !important;
      overflow: hidden; /* keep rounded corners clean */
    }
    .auth-card .card-body{
      border-radius: 22px !important;
    }

    /* logo header (match login) */
    .auth-logo-wrap{
      display:flex;
      justify-content:center;
      margin-bottom: 1.25rem;
    }
    .auth-logo{
      border-radius: 16px;
      max-height: 120px;
      width: auto;
      object-fit: contain;
    }

    /* switch logo by OS/theme preference */
    @media (prefers-color-scheme: dark){
      .auth-logo--light{ display:none; }
      .auth-logo--dark{ display:inline-block; }
    }

    @media (max-width: 420px){
      .auth-logo{ height: 56px; }
    }

    /* ===== Dark mode (synced with localStorage pwus_theme) ===== */
    html.theme-dark body{
      background: #0b1220;
      color: #e5e7eb;
    }

    html.theme-dark .auth-card{
      background: #0f172a !important;
      box-shadow: 0 10px 35px rgba(0,0,0,.35) !important;
    }

    html.theme-dark .auth-card .card-body{
      background: #0f172a !important;
      color: #e5e7eb;
    }

    html.theme-dark .form-label{
      color: #e5e7eb;
    }

    html.theme-dark .otp-box{
      background: rgba(2,6,23,.55);
      border-color: rgba(148,163,184,.28);
      color: #e5e7eb;
    }
    html.theme-dark .otp-box:focus{
      border-color: rgba(99,102,241,.95);
      box-shadow: 0 0 0 .2rem rgba(99,102,241,.25);
    }

    html.theme-dark .text-muted,
    html.theme-dark .resend-hint{
      color: rgba(226,232,240,.75) !important;
    }

    html.theme-dark .auth-divider{
      border-top-color: rgba(148,163,184,.22);
    }

    html.theme-dark .btn-outline-secondary{
      border-color: rgba(148,163,184,.35);
      color: rgba(226,232,240,.9);
    }
    html.theme-dark .btn-outline-secondary:hover{
      background: rgba(148,163,184,.12);
      border-color: rgba(148,163,184,.45);
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <div class="card shadow-sm border-0 auth-card">
          <div class="card-body p-4 p-md-5">

            <div class="auth-logo-wrap">
              <img
                src="http://127.0.0.1:8000/storage/aps_light.png"
                alt="Logo"
                class="auth-logo"
                id="authLogo"
              >
            </div>

            {{-- ...existing code (title, alerts)... --}}

            <form method="POST" action="{{ route('otp.verify') }}" class="mb-3" id="otpForm">
              @csrf

              {{-- keep backend compatible --}}
              <input type="hidden" name="otp" id="otpHidden" value="">

              <div class="mb-2">
                <label class="form-label fw-semibold d-block text-center">OTP (6 digit)</label>

                <div class="otp-wrap" id="otpWrap">
                  @for($i=0; $i<6; $i++)
                    <input
                      type="text"
                      class="otp-box"
                      inputmode="numeric"
                      pattern="[0-9]*"
                      maxlength="1"
                      autocomplete="one-time-code"
                      aria-label="OTP digit {{ $i+1 }}"
                      data-otp-index="{{ $i }}"
                    />
                  @endfor
                </div>

                <div class="text-center text-muted small">
                  Anda bisa paste 6 digit langsung.
                </div>
              </div>

              <button type="submit" class="btn btn-primary d-grid w-100 btn-curve">
                Verifikasi
              </button>

              <div class="auth-divider"></div>

              <div class="auth-actions">
                <a
                  href="{{ url()->previous() && url()->previous() !== url()->current() ? url()->previous() : route('login') }}"
                  class="btn btn-outline-secondary"
                >
                  Kembali
                </a>

                <a href="#" class="resend-link text-primary" id="resendLink">
                  Kirim ulang kode
                </a>
              </div>

              <div class="resend-hint" id="resendHint" aria-live="polite"></div>
            </form>

            {{-- external (not nested) resend form --}}
            <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" class="d-none">
              @csrf
            </form>

            {{-- ...existing code (resend + back)... --}}
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('template/assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('template/assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('template/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('template/assets/js/main.js') }}"></script>

  <script>
    (function () {
      const form = document.getElementById('otpForm');
      const hidden = document.getElementById('otpHidden');
      const inputs = Array.from(document.querySelectorAll('.otp-box'));

      if (!form || !hidden || inputs.length !== 6) return;

      function isDigit(v){ return /^[0-9]$/.test(v); }
      function focusAt(i){ inputs[Math.max(0, Math.min(5, i))].focus(); }

      // autofocus first
      focusAt(0);

      inputs.forEach((inp, idx) => {
        inp.addEventListener('input', (e) => {
          const v = (inp.value || '').trim();
          inp.value = isDigit(v) ? v : '';

          if (inp.value && idx < 5) focusAt(idx + 1);
        });

        inp.addEventListener('keydown', (e) => {
          if (e.key === 'Backspace' && !inp.value && idx > 0) {
            focusAt(idx - 1);
          }
          if (e.key === 'ArrowLeft' && idx > 0) focusAt(idx - 1);
          if (e.key === 'ArrowRight' && idx < 5) focusAt(idx + 1);
        });

        inp.addEventListener('paste', (e) => {
          const text = (e.clipboardData || window.clipboardData).getData('text') || '';
          const digits = text.replace(/\D/g, '').slice(0, 6);
          if (!digits) return;

          e.preventDefault();
          digits.split('').forEach((d, i) => { inputs[i].value = d; });
          focusAt(Math.min(digits.length, 5));
        });
      });

      form.addEventListener('submit', (e) => {
        const otp = inputs.map(i => i.value).join('');
        hidden.value = otp;

        if (!/^\d{6}$/.test(otp)) {
          e.preventDefault();
          // simple inline feedback (bootstrap alert already used for server errors)
          alert('OTP harus 6 digit.');
        }
      });

      // Resend cooldown UX (link-based) - works with external form
      const resendForm = document.getElementById('resendForm');
      const resendLink = document.getElementById('resendLink');
      const resendHint = document.getElementById('resendHint');

      const COOLDOWN_SECONDS = 60; // 1 menit
      const KEY = 'otp_resend_available_at';

      function nowSec(){ return Math.floor(Date.now() / 1000); }
      function setCooldown(seconds){
        localStorage.setItem(KEY, String(nowSec() + seconds));
        tick();
      }
      function remaining(){
        const availableAt = parseInt(localStorage.getItem(KEY) || '0', 10);
        return Math.max(0, availableAt - nowSec());
      }

      function tick(){
        if (!resendLink || !resendHint) return;

        const rem = remaining();
        const disabled = rem > 0;

        resendLink.classList.toggle('is-disabled', disabled);
        resendLink.setAttribute('aria-disabled', disabled ? 'true' : 'false');

        resendHint.textContent = disabled
          ? `Bisa kirim ulang kode dalam 1 menit (${rem} detik).`
          : 'Tidak menerima kode? Kirim ulang OTP.';
      }

      tick();
      setInterval(tick, 500);

      if (resendLink && resendForm) {
        resendLink.addEventListener('click', (e) => {
          e.preventDefault();
          if (remaining() > 0) return;

          setCooldown(COOLDOWN_SECONDS);
          resendForm.submit();
        });
      }

      // Sync logo with theme (same behavior as login)
      (function () {
        const STORAGE_KEY = 'pwus_theme';
        const logo = document.getElementById('authLogo');
        if (!logo) return;

        const saved = localStorage.getItem(STORAGE_KEY) || 'light';

        // Apply dark class to enable dark CSS
        document.documentElement.classList.toggle('theme-dark', saved === 'dark');

        logo.src = saved === 'dark'
          ? '{{ asset('storage/aps_dark.png') }}'
          : '{{ asset('storage/aps_light.png') }}';
      })();
    })();
  </script>
</body>
</html>