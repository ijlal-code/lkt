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
                                @php
                                    $userRole = Auth::user()->role;
                                    $restrictedViewers = ['auditor', 'k3', 'auditi'];
                                    $isRestrictedUser = in_array($userRole, $restrictedViewers);
                                    $isFinalStatus = ($p->status == 'Approved By System');

                                    if ($isRestrictedUser && !$isFinalStatus) { continue; }
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
                                
                                <td class="text-center">
                                    @php
                                        $statusColor = 'warning'; $icon = 'bi-hourglass-split';
                                        if($p->status == 'Approved By System') { $statusColor = 'success'; $icon = 'bi-patch-check-fill'; } 
                                        elseif(str_contains($p->status, 'Ditolak')) { $statusColor = 'danger'; $icon = 'bi-x-circle-fill'; } 
                                        elseif(str_contains($p->status, 'Disetujui')) { $statusColor = 'info'; $icon = 'bi-check-circle'; }
                                    @endphp
                                    <span class="badge-status-{{ $statusColor }} text-nowrap">
                                        <i class="bi {{ $icon }} me-1"></i> {{ $p->status }}
                                    </span>
                                </td>
                                
                                <td class="pe-4">
                                    <div class="d-flex flex-column gap-2 align-items-center">
                                        {{-- 1. Tombol LIHAT --}}
                                        <a href="{{ route('pesan.preview', $p->id) }}" class="btn btn-action-view w-100 py-1 shadow-sm">
                                            <i class="bi bi-eye me-1"></i> Lihat
                                        </a>

                                        {{-- 2. Tombol APPROVE / PDF --}}
                                        @php
                                            $canApprove = false;
                                            if(($userRole == 'sm' && $p->current_step == 'sm') || 
                                               ($userRole == 'smqa' && $p->current_step == 'smqa') || 
                                               ($userRole == 'gm' && $p->current_step == 'gm')) {
                                                $canApprove = true;
                                            }
                                        @endphp

                                        @if($canApprove)
                                            <form action="{{ route('pesan.approve', $p->id) }}" method="POST" class="w-100 form-approve-list">
                                                @csrf
                                                <button type="button" class="btn btn-success fw-bold w-100 py-1 shadow-sm btn-sm btn-approve-trigger">
                                                    Approve
                                                </button>
                                            </form>
                                        @elseif($isFinalStatus)
                                            <a href="{{ route('pesan.show', $p->id) }}?download=true" class="btn btn-primary fw-bold w-100 py-1 shadow-sm btn-sm">
                                                <i class="bi bi-download me-1"></i> PDF
                                            </a>
                                        @endif

                                        {{-- 3. Tombol HAPUS (BARU) - Khusus Admin & Staff --}}
                                        @if(in_array(Auth::user()->role, ['admin', 'staff']))
                                            <button type="button" class="btn btn-danger w-100 py-1 shadow-sm btn-sm" onclick="confirmDeleteMsg('{{ $p->id }}')">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                            
                                            <form id="delete-msg-form-{{ $p->id }}" action="{{ route('sp2a.destroy', $p->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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
        background-color: #ffffff; color: #2563eb; border: 1px solid #e2e8f0; 
        border-radius: 6px; font-size: 0.8rem; font-weight: 700; text-align: center; text-decoration: none;
    }
    .btn-action-view:hover { background-color: #2563eb; color: white; border-color: #2563eb; }
    .btn-sm { font-size: 0.75rem; border-radius: 6px; }
</style>

{{-- SCRIPT SWEETALERT (Approve & Hapus) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Listener untuk tombol Approve
        const approveButtons = document.querySelectorAll('.btn-approve-trigger');
        approveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                Swal.fire({
                    title: 'Setujui Dokumen?', text: "Approve langsung dari daftar?", icon: 'question',
                    showCancelButton: true, confirmButtonColor: '#198754', confirmButtonText: 'Ya, Approve!', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({title: 'Memproses...', didOpen: () => Swal.showLoading()});
                        form.submit();
                    }
                });
            });
        });
    });

    // Listener untuk tombol Hapus (BARU)
    function confirmDeleteMsg(id) {
        Swal.fire({
            title: 'Hapus Dokumen?',
            text: "Data SP2A ini akan dihapus permanen dari sistem!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-msg-form-' + id).submit();
            }
        });
    }

    @if(session('success')) Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false }); @endif
    @if(session('error')) Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" }); @endif
</script>
@endsection