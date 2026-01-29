@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0 text-secondary"><i class="bi bi-folder2-open me-2"></i>Daftar SP2A</h3>
        @if(in_array(Auth::user()->role, ['admin', 'staff']))
        <a href="{{ route('sp2a.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Buat Baru
        </a>
        @endif
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="px-4 py-3">Nomor / Tanggal</th>
                        <th class="py-3">Kepada</th>
                        <th class="py-3">Status Realtime</th>
                        <th class="py-3 text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sp2as as $sp2a)
                    <tr>
                        <td class="px-4">
                            @if($sp2a->nomor_sp2a) 
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary mb-1">{{ $sp2a->nomor_sp2a }}</span>
                            @else 
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border mb-1">Proses Approval</span>
                            @endif
                            <div class="small text-muted">{{ $sp2a->tanggal_surat->format('d M Y') }}</div>
                        </td>
                        
                        <td>
                            <div class="fw-bold text-dark">{{ $sp2a->kepada_nama }}</div>
                            <small class="text-muted">Dari: {{ $sp2a->dari_nama }}</small>
                        </td>

                        <td>
                            {{-- LOGIKA WARNA STATUS --}}
                            @php
                                $statusColor = 'warning'; // Default kuning
                                if($sp2a->current_step == 'finished') $statusColor = 'success';
                                if($sp2a->current_step == 'staff') $statusColor = 'danger';
                                if(str_contains($sp2a->status, 'Disetujui')) $statusColor = 'info';
                            @endphp
                            <span class="badge bg-{{ $statusColor }} rounded-pill">
                                {{ $sp2a->status }}
                            </span>
                        </td>

                        <td class="text-center py-2">
                            <div class="d-flex flex-column gap-2 px-2">
                                {{-- 1. TOMBOL DETAIL (ATAS) --}}
                                <a href="{{ route('sp2a.show', $sp2a->id) }}" class="btn btn-sm btn-outline-primary fw-bold rounded-3">
                                    Detail
                                </a>

                                {{-- 2. TOMBOL APPROVE (BAWAH) - HANYA JIKA GILIRANNYA --}}
                                @php
                                    $showApprove = false;
                                    $r = Auth::user()->role;
                                    if(($r == 'sm' && $sp2a->current_step == 'sm') || 
                                       ($r == 'smqa' && $sp2a->current_step == 'smqa') || 
                                       ($r == 'gm' && $sp2a->current_step == 'gm')) {
                                        $showApprove = true;
                                    }
                                @endphp

                                @if($showApprove)
                                    <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm rounded-3" onclick="confirmApprove('{{ $sp2a->id }}')">
                                        <i class="bi bi-check-lg me-1"></i> Approve
                                    </button>
                                    <form id="approve-form-{{ $sp2a->id }}" action="{{ route('sp2a.approve', $sp2a->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                @endif
                                
                                {{-- 3. TOMBOL REVISI (STAFF) --}}
                                @if($sp2a->current_step == 'staff' && Auth::user()->role == 'staff')
                                    <a href="{{ route('sp2a.edit', $sp2a->id) }}" class="btn btn-sm btn-warning rounded-3">Revisi</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-5 text-muted">Data kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SWEETALERT 2 SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmApprove(id) {
        Swal.fire({
            title: 'Setujui Dokumen?',
            text: "Dokumen akan diteruskan ke tahap selanjutnya.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754', // Hijau Bootstrap
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-form-' + id).submit();
            }
        })
    }

    // Menampilkan Flash Message dari Controller dengan SweetAlert
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
    @endif
</script>
@endsection