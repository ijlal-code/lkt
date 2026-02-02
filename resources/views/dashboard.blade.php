@extends('layouts.app')

@section('content')
<div class="container py-5">
    
    {{-- HEADER DASHBOARD --}}
    <div class="row justify-content-center mb-5">
        <div class="col-md-10 text-center">
            @auth
                <h1 class="fw-bold text-primary mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
                <p class="text-muted fs-5">
                    Anda login sebagai <span class="badge bg-secondary text-uppercase">{{ Auth::user()->role }}</span>. 
                    Silakan pilih menu di bawah untuk mulai bekerja.
                </p>
            @else
                <h1 class="fw-bold text-primary mb-2">Sistem Internal Audit</h1>
                <p class="text-muted mb-4">Silakan login untuk mengakses sistem.</p>
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 shadow-sm rounded-pill">Login Sekarang</a>
            @endauth
        </div>
    </div>

    @auth
    <div class="row justify-content-center g-4">
        
        {{-- ========================================== --}}
        {{-- 1. DASHBOARD KHUSUS STAFF & ADMIN (SP2A & LKT) --}}
        {{-- ========================================== --}}
        @if(in_array(Auth::user()->role, ['admin', 'staff']))
            
            {{-- Modul SP2A --}}
            <div class="col-md-4">
                <a href="{{ route('sp2a.index') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-card bg-primary text-white text-center py-5 rounded-4">
                        <div class="card-body">
                            <i class="bi bi-file-earmark-text-fill display-3 mb-3"></i>
                            <h3 class="fw-bold">SP2A</h3>
                            <p class="mb-0 opacity-75">Buat dan Kelola Surat Peringatan</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Modul LKT --}}
            <div class="col-md-4">
                <a href="{{ route('ltk.index') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-card bg-success text-white text-center py-5 rounded-4">
                        <div class="card-body">
                            <i class="bi bi-clipboard-data-fill display-3 mb-3"></i>
                            <h3 class="fw-bold">LKT</h3>
                            <p class="mb-0 opacity-75">Laporan Ketidaksesuaian</p>
                        </div>
                    </div>
                </a>
            </div>

        @endif

        {{-- ========================================== --}}
        {{-- 2. DASHBOARD KHUSUS APPROVER (SM, SMQA, GM) --}}
        {{-- ========================================== --}}
        @if(in_array(Auth::user()->role, ['sm', 'smqa', 'gm', 'admin']))
            
            {{-- Modul KOTAK PESAN (Inbox Approval) --}}
            <div class="col-md-4">
                <a href="{{ route('pesan.index') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-card bg-warning text-dark text-center py-5 rounded-4">
                        <div class="card-body">
                            <i class="bi bi-inbox-fill display-3 mb-3"></i>
                            <h3 class="fw-bold">Kotak Pesan</h3>
                            <p class="mb-0 text-muted">Cek Dokumen Masuk & Approval</p>
                        </div>
                    </div>
                </a>
            </div>

        @endif

        {{-- ========================================== --}}
        {{-- 3. DASHBOARD KHUSUS ADMIN (User Management) --}}
        {{-- ========================================== --}}
        @if(Auth::user()->role == 'admin')
            
            <div class="col-md-4">
                <a href="{{ route('users.index') }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-card bg-dark text-white text-center py-5 rounded-4">
                        <div class="card-body">
                            <i class="bi bi-people-fill display-3 mb-3"></i>
                            <h3 class="fw-bold">Kelola User</h3>
                            <p class="mb-0 opacity-75">Tambah & Edit Pengguna Sistem</p>
                        </div>
                    </div>
                </a>
            </div>

        @endif

    </div>
    @endauth

    {{-- Footer Simple --}}
    <div class="text-center mt-5 text-muted small">
        &copy; {{ date('Y') }} PT Semen Tonasa - Unit Internal Audit
    </div>
</div>

<style>
    .hover-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important; }
</style>
@endsection