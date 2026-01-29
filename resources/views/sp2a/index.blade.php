@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-secondary"><i class="bi bi-folder2-open me-2"></i>Daftar SP2A</h3>
        {{-- Tombol Buat hanya untuk Staff / Admin --}}
        @if(in_array(Auth::user()->role, ['admin', 'staff']))
        <a href="{{ route('sp2a.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-2"></i>Buat SP2A Baru
        </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">Nomor / Tanggal</th>
                            <th class="py-3">Kepada</th>
                            <th class="py-3">Status Workflow</th>
                            <th class="py-3 text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sp2as as $sp2a)
                        <tr>
                            {{-- KOLOM NOMOR --}}
                            <td class="px-4">
                                @if($sp2a->nomor_sp2a) 
                                    <div class="fw-bold text-primary">{{ $sp2a->nomor_sp2a }}</div>
                                @else 
                                    <div class="text-muted small fst-italic bg-light d-inline-block px-2 rounded border">
                                        Draft / Proses
                                    </div>
                                @endif
                                <div class="text-muted small mt-1">
                                    <i class="bi bi-calendar-event me-1"></i>{{ $sp2a->tanggal_surat->format('d M Y') }}
                                </div>
                            </td>
                            
                            {{-- KOLOM KEPADA --}}
                            <td>
                                <span class="fw-bold text-dark">{{ $sp2a->kepada_nama }}</span>
                                @if($sp2a->kepada_email)
                                    <br><small class="text-muted">{{ $sp2a->kepada_email }}</small>
                                @endif
                            </td>

                            {{-- KOLOM STATUS --}}
                            <td>
                                @if($sp2a->current_step == 'finished')
                                    <span class="badge bg-success rounded-pill">
                                        <i class="bi bi-check-all me-1"></i> Approved By System
                                    </span>
                                @elseif($sp2a->current_step == 'staff')
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="bi bi-exclamation-octagon me-1"></i> Perlu Perbaikan
                                    </span>
                                @else
                                    {{-- Status Dinamis --}}
                                    <span class="badge bg-warning text-dark border border-warning rounded-pill">
                                        <i class="bi bi-hourglass-split me-1"></i> {{ $sp2a->status }}
                                    </span>
                                @endif
                            </td>

                            {{-- KOLOM AKSI (Layout Vertikal: Detail Atas, Approve Bawah) --}}
                            <td class="text-center py-3">
                                <div class="d-flex flex-column gap-2 px-2">
                                    
                                    {{-- 1. TOMBOL DETAIL (SELALU MUNCUL UTK SEMUA ROLE) --}}
                                    <a href="{{ route('sp2a.show', $sp2a->id) }}" class="btn btn-sm btn-outline-primary fw-bold w-100">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>

                                    {{-- 2. TOMBOL APPROVE (LOGIKA KHUSUS) --}}
                                    @php
                                        $showApproveBtn = false;
                                        $role = Auth::user()->role;
                                        
                                        // Cek apakah giliran role ini untuk approve
                                        if($role == 'sm' && $sp2a->current_step == 'sm') $showApproveBtn = true;
                                        if($role == 'smqa' && $sp2a->current_step == 'smqa') $showApproveBtn = true;
                                        if($role == 'gm' && $sp2a->current_step == 'gm') $showApproveBtn = true;
                                    @endphp

                                    @if($showApproveBtn)
                                        <form action="{{ route('sp2a.approve', $sp2a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menyetujui dokumen ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success w-100 shadow-sm" title="Klik untuk Approve">
                                                <i class="bi bi-check-lg me-1"></i> Approve
                                            </button>
                                        </form>
                                    @endif

                                    {{-- 3. TOMBOL REVISI (KHUSUS STAFF) --}}
                                    @if($sp2a->current_step == 'staff' && Auth::user()->role == 'staff')
                                        <a href="{{ route('sp2a.edit', $sp2a->id) }}" class="btn btn-sm btn-warning w-100">
                                            <i class="bi bi-pencil me-1"></i> Revisi
                                        </a>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada dokumen SP2A.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection