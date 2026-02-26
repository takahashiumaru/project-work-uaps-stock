<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="{{ asset('template/') }}/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>PT. Angkasa Pratama Sejahtera</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/aps_mini.png') }}" sizes="48x48" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('template/') }}/assets/vendor/fonts/boxicons.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('template/') }}/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('template/') }}/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('template/') }}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('template/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="{{ asset('template/') }}/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('template/') }}/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('template/') }}/assets/js/config.js"></script>

    {{-- FIX: apply theme BEFORE first paint to prevent white flash --}}
    <script>
      (function () {
        const STORAGE_KEY = 'pwus_theme';
        const saved = localStorage.getItem(STORAGE_KEY) || 'light';
        const root = document.documentElement;

        // Set attributes/classes ASAP (before CSS/images settle)
        root.setAttribute('data-theme', saved);
        root.classList.toggle('dark-style', saved === 'dark');
        root.classList.toggle('light-style', saved !== 'dark');

        // Help native controls avoid light flash
        root.style.colorScheme = saved === 'dark' ? 'dark' : 'light';
      })();
    </script>

    @yield('styles')

    <style>
        /* === Global Theme Variables (Light default) === */
        :root{
            --accent:#5661f8;
            --bg:#f7fbff;
            --text:#0f172a;
            --muted:#6b7280;

            --card-bg:#ffffff;
            --card-border:#eef2f6;

            --input-bg:#ffffff;
            --input-border:#dfe4ea;
            --table-head:#fbfcff;

            --nav-bg:#ffffff;
            --sidebar-bg:#ffffff;
            --sidebar-text:#0f172a;
            --sidebar-muted:#6b7280;
            --divider:#eef2f6;
        }
        html[data-theme="dark"]{
            --bg:#0b1220;
            --text:#e5e7eb;
            --muted:#9ca3af;

            --card-bg:#0f172a;
            --card-border:#1f2937;

            --input-bg:#0b1220;
            --input-border:#243244;
            --table-head:#0b1220;

            --nav-bg:#0f172a;
            --sidebar-bg:#0b1220;
            --sidebar-text:#e5e7eb;
            --sidebar-muted:#9ca3af;
            --divider:#1f2937;
        }

        /* FIX: ensure first paint background matches theme */
        html, body{
            background: var(--bg) !important;
            color: var(--text) !important;
        }

        /* Prevent transition flash on initial load */
        html *{
            transition: none !important;
        }
        /* Re-enable transitions after first render */
        html.transitions-ready *{
            transition: background .18s ease, color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        body{ background: var(--bg) !important; color: var(--text) !important; }

        /* Cards */
        .card, .card-clean{
            background: var(--card-bg) !important;
            border-color: var(--card-border) !important;
        }
        .card-header, .card-header-clean{
            background: var(--card-bg) !important;
            border-color: var(--card-border) !important;
            color: var(--text) !important;
        }

        /* Navbar (top bar) */
        html[data-theme="dark"] .layout-navbar,
        html[data-theme="dark"] .bg-navbar-theme{
            background: var(--nav-bg) !important;
            border-color: var(--divider) !important;
        }
        html[data-theme="dark"] .layout-navbar .nav-link,
        html[data-theme="dark"] .layout-navbar .navbar-nav .nav-item a,
        html[data-theme="dark"] .layout-navbar .dropdown-toggle,
        html[data-theme="dark"] .layout-navbar .bx{
            color: var(--text) !important;
        }
        html[data-theme="dark"] .dropdown-menu{
            background: var(--card-bg) !important;
            color: var(--text) !important;
            border-color: var(--card-border) !important;
        }
        html[data-theme="dark"] .dropdown-item{ color: var(--text) !important; }
        html[data-theme="dark"] .dropdown-item:hover{ background: rgba(255,255,255,0.06) !important; }

        /* Sidebar */
        html[data-theme="dark"] #layout-menu,
        html[data-theme="dark"] .bg-menu-theme{
            background: var(--sidebar-bg) !important;
            border-color: var(--divider) !important;
        }
        html[data-theme="dark"] .menu-inner .menu-item .menu-link{
            color: var(--sidebar-text) !important;
        }
        html[data-theme="dark"] .menu-inner .menu-item .menu-link .menu-icon{
            color: var(--sidebar-text) !important;
        }
        html[data-theme="dark"] .menu-inner .menu-sub .menu-link{
            color: var(--sidebar-muted) !important;
        }
        html[data-theme="dark"] .menu-item.active > .menu-link,
        html[data-theme="dark"] .menu-item.open > .menu-link{
            background: rgba(86,97,248,0.18) !important;
            color: var(--sidebar-text) !important;
        }
        html[data-theme="dark"] .menu-inner-shadow{ background: transparent !important; }

        /* Tables */
        .table{ color: var(--text) !important; }
        .table thead th{
            background: var(--table-head) !important;
            color: var(--text) !important;
            border-color: var (--card-border) !important;
        }
        .table tbody td{ border-color: var(--card-border) !important; }
        html[data-theme="dark"] .table-hover tbody tr:hover{
            background: rgba(255,255,255,0.04) !important;
        }

        /* Form controls */
        .form-control, .form-select, textarea{
            background: var(--input-bg) !important;
            color: var (--text) !important;
            border-color: var(--input-border) !important;
        }
        .form-control::placeholder{ color: var(--muted) !important; }
        .form-label{ color: var (--text) !important; }
        .text-muted{ color: var(--muted) !important; }

        /* Breadcrumb visibility in dark */
        html[data-theme="dark"] .breadcrumb-item,
        html[data-theme="dark"] .breadcrumb-item a{
            color: var(--muted) !important;
        }
        html[data-theme="dark"] .breadcrumb-item.active{ color: var(--text) !important; }

        /* Badges/labels that become unreadable */
        html[data-theme="dark"] .badge.bg-label-primary{
            background: rgba(86,97,248,0.18) !important;
            color: #c7d2fe !important;
            border: 1px solid rgba(86,97,248,0.25) !important;
        }

        /* Modals */
        .modal-content{
            background: var(--card-bg) !important;
            color: var(--text) !important;
            border-color: var(--card-border) !important;
        }

        /* Footer */
        html[data-theme="dark"] .content-footer,
        html[data-theme="dark"] .footer,
        html[data-theme="dark"] .bg-footer-theme{
            background: var(--card-bg) !important;
            color: var(--text) !important;
            border-top: 1px solid var(--divider) !important;
        }
        html[data-theme="dark"] .footer-link{ color: var(--muted) !important; }

        /* Pagination */
        html[data-theme="dark"] .pagination .page-link{
            background: var(--card-bg) !important;
            color: var(--text) !important;
            border-color: var(--card-border) !important;
        }
        html[data-theme="dark"] .pagination .page-item.active .page-link{
            background: rgba(86,97,248,0.22) !important;
            border-color: rgba(86,97,248,0.35) !important;
            color: #c7d2fe !important;
        }
        html[data-theme="dark"] .pagination .page-item.disabled .page-link{
            background: rgba(255,255,255,0.02) !important;
            color: var(--muted) !important;
            border-color: var(--card-border) !important;
        }

        /* Select2 (bootstrap-5 theme) */
        html[data-theme="dark"] .select2-container--bootstrap-5 .select2-selection{
            background-color: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text) !important;
        }
        html[data-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered{
            color: var(--text) !important;
        }
        html[data-theme="dark"] .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder{
            color: var(--muted) !important;
        }
        html[data-theme="dark"] .select2-container--bootstrap-5 .select2-dropdown{
            background-color: var(--card-bg) !important;
            border-color: var(--card-border) !important;
            color: var(--text) !important;
        }
        html[data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option{
            color: var(--text) !important;
        }
        html[data-theme="dark"] .select2-container--bootstrap-5 .select2-results__option--highlighted{
            background: rgba(86,97,248,0.22) !important;
            color: #e5e7eb !important;
        }
        html[data-theme="dark"] .select2-container--bootstrap-5 .select2-search__field{
            background: var(--input-bg) !important;
            color: var(--text) !important;
            border-color: var(--input-border) !important;
        }

        /* FIX: table wrapper cards that were forced white in per-page CSS */
        html[data-theme="dark"] .table-responsive{
            background: var(--card-bg) !important;
            border-color: var(--card-border) !important;
        }

        /* FIX: home stat cards text (some pages hardcode dark text) */
        html[data-theme="dark"] .stat-value{
            color: var(--text) !important;
        }
        html[data-theme="dark"] .stat-label,
        html[data-theme="dark"] .subtitle,
        html[data-theme="dark"] .muted-sm{
            color: var(--muted) !important;
        }

        /* Make sure generic headings inside cards stay readable */
        html[data-theme="dark"] h1,
        html[data-theme="dark"] h2,
        html[data-theme="dark"] h3,
        html[data-theme="dark"] h4,
        html[data-theme="dark"] h5,
        html[data-theme="dark"] h6{
            color: var(--text) !important;
        }

        /* === Elegant Sidebar (SAFE: scoped, no layout/position overrides) === */
        #layout-menu{
            border-right: 1px solid var(--divider);
        }

        /* Brand area */
        #layout-menu .app-brand{
            padding: 14px 16px !important;
            border-bottom: 1px solid var(--divider) !important;
        }
        #layout-menu .app-brand-logo img{
            width: 56px !important;
            height: auto !important;
            display: block;
        }

        /* Inner spacing */
        #layout-menu .menu-inner{
            padding: 10px 10px 14px !important;
        }

        /* Links as pills */
        #layout-menu .menu-link{
            border-radius: 12px !important;
            margin: 4px 6px !important;
            padding: 10px 12px !important;
            transition: background .18s ease, box-shadow .18s ease, transform .18s ease, color .18s ease;
        }
        #layout-menu .menu-link:hover{
            background: rgba(86,97,248,0.10) !important;
            transform: translateY(-1px);
        }
        html[data-theme="dark"] #layout-menu .menu-link:hover{
            background: rgba(255,255,255,0.05) !important;
        }

        /* Active/open */
        #layout-menu .menu-item.active > .menu-link,
        #layout-menu .menu-item.open > .menu-link{
            background: rgba(86,97,248,0.18) !important;
            box-shadow: 0 10px 18px rgba(86,97,248,0.12);
            font-weight: 800;
            position: relative;
        }
        #layout-menu .menu-item.active > .menu-link::before,
        #layout-menu .menu-item.open > .menu-link::before{
            content:"";
            position:absolute;
            left:-2px;
            top:10px;
            bottom:10px;
            width:3px;
            border-radius:999px;
            background: var(--accent);
        }

        /* Submenu container */
        #layout-menu .menu-sub{
            margin: 6px 8px 10px !important;
            padding: 8px 6px !important;
            border-radius: 12px;
            border: 1px solid var(--divider);
            background: rgba(15,23,42,0.02);
        }
        html[data-theme="dark"] #layout-menu .menu-sub{
            background: rgba(255,255,255,0.03);
        }

        /* Submenu link tighter */
        #layout-menu .menu-sub .menu-link{
            margin: 2px 4px !important;
            padding: 8px 10px !important;
            border-radius: 10px !important;
        }

        /* Icons look consistent */
        #layout-menu .menu-icon{
            font-size: 1.15rem !important;
        }

        /* Remove inner shadow overlay that looks “old” */
        #layout-menu .menu-inner-shadow{
            display: none !important;
        }

        /* === FIX: prevent sidebar being "covered" / overlapped === */
        #layout-menu{
            position: relative;           /* no behavior change, just stacking context */
            z-index: 1100;                /* above content/backdrop */
        }
        .layout-overlay{
            z-index: 1090;
        }

        /* === Reduce large gap between sidebar and content (desktop) === */
        @media (min-width: 1200px){
            /* Trim left padding inside main page area */
            .layout-page{
                padding-left: 12px !important;
            }

            /* Also reduce default container padding a bit (optional but helps) */
            .container-xxl{
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            /* Detached navbar tends to keep wide margin; tighten it */
            #layout-navbar.container-xxl{
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        /* === HIDE "tile"/indicator on menu items (sidebar only) === */
        #layout-menu .menu-item > .menu-link::before,
        #layout-menu .menu-item > .menu-link::after{
            content: none !important;
            display: none !important;
        }

        /* If active/open looks like a tile, flatten it */
        #layout-menu .menu-item.active > .menu-link,
        #layout-menu .menu-item.open > .menu-link{
            box-shadow: none !important;     /* remove tile shadow */
            background: transparent !important; /* remove tile bg */
            font-weight: 800;               /* keep emphasis */
        }

        /* Keep a subtle text/icon emphasis instead */
        #layout-menu .menu-item.active > .menu-link,
        #layout-menu .menu-item.open > .menu-link{
            color: var(--accent) !important;
        }
        #layout-menu .menu-item.active > .menu-link .menu-icon,
        #layout-menu .menu-item.open > .menu-link .menu-icon{
            color: var(--accent) !important;
        }

        /* Optional: keep hover background only */
        #layout-menu .menu-link:hover{
            background: rgba(86,97,248,0.08) !important;
            transform: none !important;
        }

        /* FIX: circles must not become oval */
        .circle{
            width: 40px;
            height: 40px;
            border-radius: 50% !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex: 0 0 40px;
        }

        /* Navbar avatar: ensure perfect circle even if source image is rectangular */
        .avatar img,
        .avatar .w-px-40{
            width: 40px !important;
            height: 40px !important;
            object-fit: cover;
            border-radius: 50% !important;
            display: block;
        }

        /* Theme toggle icon-only button */
        #themeToggleBtn{
            width: 38px;
            height: 38px;
            padding: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            margin-left: 10px; /* NEW */
        }
        #themeToggleBtn i{
            font-size: 1.05rem;
            line-height: 1;
        }

        /* Theme toggle icon colors (elegant + clear) */
        #themeToggleBtn{
            border-color: var(--divider);
        }
        #themeToggleBtn:hover{
            background: rgba(86,97,248,0.08);
            border-color: rgba(86,97,248,0.25);
        }
        html[data-theme="dark"] #themeToggleBtn:hover{
            background: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.14);
        }

        /* Default (light theme): moon shows accent */
        #themeToggleBtn i.bi-moon-stars{
            color: var(--accent) !important;
        }
        /* Dark theme: sun shows warm amber */
        #themeToggleBtn i.bi-sun{
            color: #fbbf24 !important; /* amber */
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('home') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('storage/aps.jpeg') }}" alt="Logo" width="75">
                        </span>
                        <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">Sneat</span> -->
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <!-- active -->
                    <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Home</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('products.*') || request()->routeIs('requests.*') ? 'open active' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-package"></i>
                            <div data-i18n="Apply Leave">Produk</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <a href="{{ route('products.index') }}" class="menu-link">
                                    <div data-i18n="Product">Produk</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                                <a href="{{ route('requests.index') }}" class="menu-link">
                                    <div data-i18n="Request">Request Ho</div>
                                </a>
                            </li>
                            {{-- @if (in_array(Auth::user()->role, ['Leader', 'Ass Leader', 'Admin', 'SPV']))
                            <li class="menu-item">
                                <a href="{{ route('leaves.index') }}" class="menu-link">
                                    <div data-i18n="Approval">Approval Leave</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('leaves.laporan') }}" class="menu-link">
                                    <div data-i18n="Report">Laporan Leave</div>
                                </a>
                            </li> --}}
                            {{-- @endif --}}
                        </ul>
                    </li>
                    <li class="menu-item {{ request()->routeIs('stock-logs.*') ? 'active' : '' }}">
                        <a href="{{ route('stock-logs.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-history"></i>
                            <div data-i18n="StockLogs">Stock Logs</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('work-reports.*') ? 'active' : '' }}">
                        <a href="{{ route('work-reports.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bi bi-clipboard-check"></i>
                            <div data-i18n="StockLogs">Laporan Pekerjaan</div>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('work-reports.*') ? 'active' : '' }}"
                           href="{{ route('work-reports.index') }}">
                            <i class="bi bi-clipboard-check"></i>
                            <span class="ms-1">Laporan Pekerjaan</span>
                        </a>
                    </li> --}}
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav
                    class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <!-- <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                </div>
              </div> -->
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        @if (!empty(Auth::user()->profile_picture))
                                        <img src="{{ asset('storage/photo/' . Auth::user()->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" />
                                        @else
                                        <img src="{{ asset('storage/photo/user.jpg') }}" alt class="w-px-40 h-auto rounded-circle" />
                                        @endif
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        @if (!empty(Auth::user()->profile_picture))
                                                        <img src="{{ asset('storage/photo/' . Auth::user()->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" />
                                                        @else
                                                        <img src="{{ asset('storage/photo/user.jpg') }}" alt class="w-px-40 h-auto rounded-circle" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->fullname }}</span>
                                                    <small class="text-muted">{{ Auth::user()->role }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                            <li class="nav-item">
                                <button type="button" id="themeToggleBtn" class="btn btn-outline-secondary btn-sm" aria-label="Toggle theme">
                                    <i class="bi bi-moon-stars"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️
                                <!-- by <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a> -->
                            </div>
                            <!-- <div>
                  <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                  <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>
                  <a
                    href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                    target="_blank"
                    class="footer-link me-4"
                    >Documentation</a
                  >
                  <a
                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                    target="_blank"
                    class="footer-link me-4"
                    >Support</a
                  >
                </div> -->
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('template/') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('template/') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('template/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('template/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ asset('template/') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('template/') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('template/') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('template/') }}/assets/js/dashboards-analytics.js"></script>

    <!-- Date & Time Script -->
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const formattedDate = now.toLocaleDateString('id-ID', options);
            document.getElementById('tanggalSekarang').textContent = formattedDate;
        }

        // Update immediately
        updateDateTime();

        // Update every second
        setInterval(updateDateTime, 1000);
    </script>

    <script>
    (function() {
        const STORAGE_KEY = 'pwus_theme';
        const root = document.documentElement;
        const btn = document.getElementById('themeToggleBtn');

        function setBtnIcon(theme){
            if (!btn) return;
            btn.innerHTML = theme === 'dark'
              ? '<i class="bi bi-sun" aria-hidden="true"></i>'
              : '<i class="bi bi-moon-stars" aria-hidden="true"></i>';
        }

        function applyTheme(theme) {
            root.setAttribute('data-theme', theme);
            root.classList.toggle('dark-style', theme === 'dark');
            root.classList.toggle('light-style', theme !== 'dark');
            root.style.colorScheme = theme === 'dark' ? 'dark' : 'light';
            setBtnIcon(theme);
        }

        const saved = localStorage.getItem(STORAGE_KEY) || 'light';
        applyTheme(saved);

        if (btn) {
            btn.addEventListener('click', () => {
                const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                localStorage.setItem(STORAGE_KEY, next);
                applyTheme(next);
            });
        }
    })();
    </script>

    <script>
      // Re-enable transitions after page is shown (avoid blink/flash)
      window.addEventListener('DOMContentLoaded', () => {
        document.documentElement.classList.add('transitions-ready');
      });
    </script>

    @yield('scripts')
</body>

</html>
