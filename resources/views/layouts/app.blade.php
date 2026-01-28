<!doctype html>
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
        body { overflow-x: hidden; }
        #wrapper { display: flex; width: 100%; height: 100vh; }
        #sidebar-wrapper {
            min-height: 100vh; width: 250px; margin-left: 0;
            transition: margin 0.25s ease-out;
            background-color: #343a40; color: white;
        }
        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem; font-size: 1.2rem; font-weight: bold;
            background-color: #212529; color: #fff;
        }
        #sidebar-wrapper .list-group-item {
            background-color: #343a40; color: #cfd2d6; border: none; padding: 12px 20px;
        }
        #sidebar-wrapper .list-group-item:hover {
            background-color: #495057; color: #fff;
        }
        #sidebar-wrapper .list-group-item.active {
            background-color: #0d6efd; color: #fff;
        }
        .sidebar-submenu .list-group-item {
            padding-left: 40px; background-color: #2c3034 !important; font-size: 0.95rem;
        }
        #page-content-wrapper { width: 100%; overflow-y: auto; }
        @media (max-width: 768px) {
            #sidebar-wrapper { margin-left: -250px; }
            #wrapper.toggled #sidebar-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div id="wrapper">
        
        <div class="border-end" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom">Sistem Audit</div>
            
            <div class="list-group list-group-flush">
                
                <a href="{{ url('/') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>

                @auth
                    @if(Auth::user()->role == 'admin')
                        
                        <div class="sidebar-heading mt-2 border-top pt-2" style="font-size: 0.9rem; color: #adb5bd;">
                            MENU ADMIN
                        </div>

                        <a href="#submenuLKT" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse">
                            <i class="bi bi-file-earmark-text me-2"></i> Manajemen LKT
                        </a>
                        <div class="collapse sidebar-submenu {{ Request::is('ltk*') ? 'show' : '' }}" id="submenuLKT">
                            <a href="{{ route('ltk.index') }}" class="list-group-item list-group-item-action">Daftar LKT</a>
                            <a href="{{ route('ltk.create') }}" class="list-group-item list-group-item-action">Buat LKT Baru</a>
                        </div>

                        <a href="#submenuSP2A" class="list-group-item list-group-item-action dropdown-toggle" data-bs-toggle="collapse">
                            <i class="bi bi-exclamation-triangle me-2"></i> Manajemen SP2A
                        </a>
                        <div class="collapse sidebar-submenu {{ Request::is('sp2a*') ? 'show' : '' }}" id="submenuSP2A">
                            <a href="{{ route('sp2a.index') }}" class="list-group-item list-group-item-action">Daftar SP2A</a>
                            <a href="{{ route('sp2a.create') }}" class="list-group-item list-group-item-action">Buat SP2A Baru</a>
                        </div>

                        <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action {{ Request::is('users*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill me-2"></i> Manajemen User
                        </a>

                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-graph-up me-2"></i> Laporan & Grafik
                        </a>

                    @else
                        
                        <div class="sidebar-heading mt-2 border-top pt-2" style="font-size: 0.9rem; color: #adb5bd;">
                            MENU USER
                        </div>

                        <a href="{{ route('pesan.index') }}" class="list-group-item list-group-item-action {{ Request::is('pesan*') ? 'active' : '' }}">
                            <i class="bi bi-inbox-fill me-2"></i> Pesan Masuk 
                        </a>

                    @endif
                @endauth

            </div>
        </div>

        <div id="page-content-wrapper">
            
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary btn-sm" id="sidebarToggle">☰ Menu</button>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} 
                                        <span class="badge bg-secondary ms-1">{{ ucfirst(Auth::user()->role) }}</span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-4">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.querySelector('#wrapper').classList.toggle('toggled');
                });
            }
        });
    </script>
</body>
</html>