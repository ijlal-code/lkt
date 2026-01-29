@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-end">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-inbox-fill text-primary me-2"></i> Kotak Masuk Dokumen
            </h2>
            <p class="text-muted mb-0">Kelola persetujuan dan riwayat dokumen SP2A dalam satu sistem terintegrasi.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-flex align-items-center bg-white border rounded-pill px-3 py-2 shadow-sm">
                <div class="rounded-circle bg-primary me-2" style="width: 10px; height: 10px;"></div>
                <span class="small fw-bold text-uppercase text-muted">Akses: {{ Auth::user()->role }}</span>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            @if($pesan->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-envelope-open text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                    <h5 class="text-dark fw-bold mt-3">Kotak Masuk Kosong</h5>
                    <p class="text-muted">Tidak ada dokumen yang perlu diproses saat ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-table">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3 text-secondary small fw-bold">PENGIRIM</th>
                                <th class="py-3 text-secondary small fw-bold">NOMOR SP2A</th>
                                <th class="py-3 text-secondary small fw-bold">PERIHAL</th>
                                <th class="py-3 text-secondary small fw-bold">TANGGAL</th>
                                <th class="py-3 text-secondary small fw-bold text-center">STATUS</th>
                                <th class="text-center pe-4 py-3 text-secondary small fw-bold" style="width: 150px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesan as $p)
                                {{-- 
                                    LOGIKA FILTER KHUSUS:
                                    Hanya peran 'auditor', 'k3', dan 'auditi' yang dibatasi.
                                    Mereka HANYA boleh melihat dokumen yang statusnya 'Approved By System'.
                                --}}
                                @php
                                    $userRole = Auth::user()->role;
                                    
                                    // Daftar peran yang hanya boleh melihat hasil akhir
                                    $restrictedViewers = ['auditor', 'k3', 'auditi'];
                                    
                                    $isRestrictedUser = in_array($userRole, $restrictedViewers);
                                    $isFinalStatus = ($p->status == 'Approved By System');

                                    // Jika user dibatasi DAN status belum final -> SKIP (Jangan tampilkan)
                                    if ($isRestrictedUser && !$isFinalStatus) {
                                        continue; 
                                    }
                                @endphp

                            <tr>
                                <td class="ps-4 py-4">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $p->dari_nama }}</div>
                                        <div class="small text-muted text-nowrap">{{ $p->dari_jabatan ?? 'User Internal' }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($p->nomor_sp2a)
                                        <span class="font-monospace fw-bold text-primary text-nowrap">{{ $p->nomor_sp2a }}</span>
                                    @else
                                        <span class="text-muted small fst-italic">Draft / Proses</span>
                                    @endif
                                </td>
                                <td><div class="text-dark fw-medium">{{ Str::limit($p->perihal, 35) }}</div></td>
                                <td>
                                    <div class="text-dark text-nowrap">{{ \Carbon\Carbon::parse($p->tanggal_surat)->format('d/m/Y') }}</div>
                                </td>
                                
                                {{-- KOLOM STATUS --}}
                                <td class="text-center">
                                    @php
                                        $statusColor = 'warning';
                                        $icon = 'bi-hourglass-split';
                                        
                                        if($p->status == 'Approved By System') {
                                            $statusColor = 'success';
                                            $icon = 'bi-patch-check-fill';
                                        } elseif(str_contains($p->status, 'Ditolak')) {
                                            $statusColor = 'danger';
                                            $icon = 'bi-x-circle-fill';
                                        } elseif(str_contains($p->status, 'Disetujui')) {
                                            $statusColor = 'info';
                                            $icon = 'bi-check-circle';
                                        }
                                    @endphp

                                    <span class="badge-status-{{ $statusColor }} text-nowrap">
                                        <i class="bi {{ $icon }} me-1"></i> {{ $p->status }}
                                    </span>
                                </td>
                                
                                {{-- KOLOM AKSI --}}
                                <td class="pe-4">
                                    <div class="d-flex flex-column gap-2 align-items-center">
                                        {{-- 1. Tombol LIHAT (Semua User Punya) --}}
                                        <a href="{{ route('pesan.preview', $p->id) }}" class="btn btn-action-view w-100 py-1 shadow-sm">
                                            <i class="bi bi-eye me-1"></i> Lihat
                                        </a>

                                        {{-- 2. Logika Tombol Kedua (Approve vs Download) --}}
                                        @php
                                            $canApprove = false;
                                            // Cek hak approve (Hanya SM, SMQA, GM)
                                            if(($userRole == 'sm' && $p->current_step == 'sm') || 
                                               ($userRole == 'smqa' && $p->current_step == 'smqa') || 
                                               ($userRole == 'gm' && $p->current_step == 'gm')) {
                                                $canApprove = true;
                                            }
                                        @endphp

                                        @if($canApprove)
                                            {{-- Jika Approver: Tampilkan Tombol Approve --}}
                                            <form action="{{ route('pesan.approve', $p->id) }}" method="POST" class="w-100 form-approve-list">
                                                @csrf
                                                <button type="button" class="btn btn-success fw-bold w-100 py-1 shadow-sm btn-sm btn-approve-trigger">
                                                    Approve
                                                </button>
                                            </form>

                                        @elseif($isFinalStatus)
                                            {{-- Jika Dokumen Final (User siapapun): Tampilkan Download PDF --}}
                                            <a href="{{ route('pesan.show', $p->id) }}?download=true" class="btn btn-danger fw-bold w-100 py-1 shadow-sm btn-sm">
                                                <i class="bi bi-download me-1"></i> PDF
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; }
    .custom-table thead { background-color: #fcfcfd; border-bottom: 1px solid #f1f1f1; }
    .custom-table tbody tr:hover { background-color: #f8faff; }
    
    .badge-status-success { background-color: #ecfdf5; color: #059669; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; }
    .badge-status-warning { background-color: #fffbeb; color: #d97706; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; }
    .badge-status-info { background-color: #eff6ff; color: #1d4ed8; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; } 
    .badge-status-danger { background-color: #fef2f2; color: #dc2626; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; }

    .btn-action-view { 
        background-color: #ffffff; 
        color: #2563eb; 
        border: 1px solid #e2e8f0; 
        border-radius: 6px; 
        font-size: 0.8rem; 
        font-weight: 700;
        text-align: center;
        text-decoration: none;
    }
    .btn-action-view:hover { background-color: #2563eb; color: white; border-color: #2563eb; }
    
    .btn-sm { font-size: 0.75rem; border-radius: 6px; }
</style>

{{-- SCRIPT SWEETALERT UNTUK TOMBOL APPROVE --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const approveButtons = document.querySelectorAll('.btn-approve-trigger');
        approveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Setujui Dokumen?',
                    text: "Anda akan menyetujui dokumen ini secara langsung dari daftar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Approve!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading() }
                        });
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection