<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
        }

        body { 
            overflow-x: hidden; 
            background-color: #f8fafc; 
        }

        #wrapper { 
            display: flex; 
            width: 100%; 
            min-height: 100vh; 
        }

        /* Sidebar Styling */
        #sidebar-wrapper {
            width: var(--sidebar-width);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            background-color: #212529; 
            color: white;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease-out;
        }

        /* Styling khusus untuk LOGO ATAS saja */
        #sidebar-wrapper .sidebar-heading {
            padding: 1.5rem 1.25rem;
            font-size: 1.25rem;
            font-weight: bold;
            background-color: #1a1d20;
            color: #fff;
            text-align: center;
            letter-spacing: 1px;
        }

        /* List Group Styling */
        #sidebar-wrapper .list-group-item {
            background-color: transparent;
            color: #adb5bd;
            border: none;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }

        #sidebar-wrapper .list-group-item:hover {
            background-color: #343a40;
            color: #fff;
            padding-left: 25px;
        }

        #sidebar-wrapper .list-group-item.active {
            background-color: #0d6efd;
            color: #fff;
        }

        .sidebar-submenu .list-group-item {
            padding-left: 45px !important;
            background-color: #1a1d20 !important;
            font-size: 0.9rem;
        }

        /* User Section & Logout at Bottom */
        .sidebar-footer {
            margin-top: auto;
            padding: 20px;
            background-color: #1a1d20;
            border-top: 1px solid #343a40;
        }

        .user-info {
            background: #2c3034;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        /* Main Content Styling */
        #page-content-wrapper { 
            width: 100%; 
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.25s ease-out;
        }

        /* Mobile Adjustments (Hamburger Menu Only on Mobile) */
        .mobile-header {
            display: none;
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        @media (max-width: 992px) {
            #sidebar-wrapper {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            #page-content-wrapper {
                margin-left: 0;
            }
            .mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }

        .overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        #wrapper.toggled .overlay {
            display: block;
        }
    </style>
</head>
<body>

    <div id="wrapper">
        <div class="overlay" id="sidebarOverlay"></div>

        <div id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom">
                <i class="bi bi-shield-lock me-2"></i>SISTEM AUDIT
            </div>
            
            <div class="list-group list-group-flush flex-grow-1 overflow-auto">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ Request::is('/') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>

                @auth
                    {{-- AREA KHUSUS ADMIN & STAFF --}}
                    @if(in_array(Auth::user()->role, ['admin', 'staff']))
                        
                        <div class="px-3 mt-3 mb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                            Menu Operasional
                        </div>

                        {{-- Menu LKT --}}
                        <a href="#submenuLKT" class="list-group-item list-group-item-action dropdown-toggle d-flex justify-content-between align-items-center" data-bs-toggle="collapse">
                            <span><i class="bi bi-file-earmark-text me-2"></i> LKT</span>
                        </a>
                        <div class="collapse sidebar-submenu {{ Request::is('ltk*') ? 'show' : '' }}" id="submenuLKT">
                            <a href="{{ route('ltk.index') }}" class="list-group-item list-group-item-action {{ Request::is('ltk') ? 'text-white fw-bold' : '' }}">Daftar LKT</a>
                            <a href="{{ route('ltk.create') }}" class="list-group-item list-group-item-action {{ Request::is('ltk/create') ? 'text-white fw-bold' : '' }}">Buat Baru</a>
                        </div>

                        {{-- Menu SP2A --}}
<a href="#submenuSP2A" class="list-group-item list-group-item-action dropdown-toggle d-flex justify-content-between align-items-center" data-bs-toggle="collapse">
    <span><i class="bi bi-exclamation-triangle me-2"></i> SP2A</span>
</a>
<div class="collapse sidebar-submenu {{ Request::is('sp2a*') || Request::is('settings*') ? 'show' : '' }}" id="submenuSP2A">
    <a href="{{ route('sp2a.index') }}" class="list-group-item list-group-item-action {{ Request::is('sp2a') ? 'text-white fw-bold' : '' }}">
        Daftar SP2A
    </a>
    <a href="{{ route('sp2a.create') }}" class="list-group-item list-group-item-action {{ Request::is('sp2a/create') ? 'text-white fw-bold' : '' }}">
        Buat Baru
    </a>
    {{-- Menu Pengaturan diletakkan di dalam submenu agar style padding-nya sama --}}
    <a href="{{ route('settings.index') }}" class="list-group-item list-group-item-action {{ Request::is('settings*') ? 'text-white fw-bold' : '' }}">
        Pengaturan
    </a>
</div>


                        {{-- Manajemen User: HANYA ADMIN (Staff tidak melihat ini) --}}
                        @if(Auth::user()->role == 'admin')
                            <div class="px-3 mt-3 mb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                                Admin Area
                            </div>
                            <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action {{ Request::is('users*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill me-2"></i> Manajemen User
                            </a>
                        @endif

                    @else
                        {{-- AREA KHUSUS APPROVER (SM, SMQA, GM) --}}
                        <div class="px-3 mt-3 mb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                            Menu Personil
                        </div>

                        <a href="{{ route('pesan.index') }}" class="list-group-item list-group-item-action {{ Request::is('pesan*') ? 'active' : '' }}">
                            <i class="bi bi-inbox-fill me-2"></i> Pesan Masuk 
                        </a>
                    @endif

                    {{-- Monitoring Section (Bisa dilihat oleh Approver) --}}
                    @if(Auth::check() && in_array(Auth::user()->role, ['sm', 'smqa', 'gm', 'admin']))
                        <div class="px-3 mt-3 mb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                            Monitoring
                        </div>
                        <a class="list-group-item list-group-item-action {{ request()->routeIs('sp2a.history') ? 'active' : '' }}" href="{{ route('sp2a.history') }}">
                            <i class="bi bi-clock-history me-2"></i> Riwayat Approval
                        </a>
                    @endif
                @endauth
            </div>

            @auth
            <div class="sidebar-footer">
                <div class="user-info shadow-sm">
                    <div class="small text-muted mb-1">Login sebagai:</div>
                    <div class="fw-bold text-truncate">{{ Auth::user()->name }}</div>
                    <span class="badge bg-primary text-uppercase mt-1" style="font-size: 0.65rem;">{{ Auth::user()->role }}</span>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100 py-2 fw-bold">
                        <i class="bi bi-box-arrow-right me-2"></i> LOGOUT
                    </button>
                </form>
            </div>
            @endauth
        </div>

        <div id="page-content-wrapper">
            <div class="mobile-header">
                <button class="btn btn-dark" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <span class="fw-bold">SP2A APP</span>
                <div></div> 
            </div>

            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.querySelector('#sidebarToggle');
            const overlay = document.querySelector('#sidebarOverlay');
            const wrapper = document.querySelector('#wrapper');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    wrapper.classList.toggle('toggled');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    wrapper.classList.remove('toggled');
                });
            }
        });
    </script>
    {{-- SWEETALERT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>