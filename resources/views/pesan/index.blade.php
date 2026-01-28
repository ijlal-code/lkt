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
                            <tr>
                                <td class="ps-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder bg-light text-primary fw-bold rounded-circle me-3">
                                            {{ strtoupper(substr($p->dari_nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $p->dari_nama }}</div>
                                            <div class="small text-muted text-nowrap">{{ $p->dari_jabatan ?? 'User Internal' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="font-monospace fw-bold text-primary text-nowrap">{{ $p->nomor_sp2a }}</span></td>
                                <td><div class="text-dark fw-medium">{{ Str::limit($p->perihal, 35) }}</div></td>
                                <td>
                                    <div class="text-dark text-nowrap">{{ \Carbon\Carbon::parse($p->tanggal_surat)->format('d/m/Y') }}</div>
                                    <div class="small text-muted text-xs text-nowrap">{{ \Carbon\Carbon::parse($p->tanggal_surat)->diffForHumans() }}</div>
                                </td>
                                <td class="text-center">
                                    @if($p->status == 'Approved By System')
                                        <span class="badge-status-success text-nowrap"><i class="bi bi-patch-check-fill me-1"></i> Terverifikasi</span>
                                    @else
                                        <span class="badge-status-warning text-nowrap"><i class="bi bi-clock-history me-1"></i> Menunggu K3</span>
                                    @endif
                                </td>
                                <td class="pe-4">
    <div class="d-flex flex-column gap-2 align-items-center">
        <a href="{{ route('pesan.preview', $p->id) }}" class="btn btn-action-view w-100 py-1 shadow-sm">
            Lihat
        </a>

        @if(Auth::user()->role == 'k3' && $p->status != 'Approved By System')
            <form action="{{ route('pesan.approve', $p->id) }}" method="POST" class="w-100">
                @csrf
                <button type="submit" class="btn btn-success fw-bold w-100 py-1 shadow-sm btn-sm" onclick="return confirm('Setujui dokumen ini?')">
                    Approve
                </button>
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
    .avatar-placeholder { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0; flex-shrink: 0; }
    .badge-status-success { background-color: #ecfdf5; color: #059669; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; }
    .badge-status-warning { background-color: #fffbeb; color: #d97706; padding: 6px 14px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; }
    
    /* Styling Tombol Lihat */
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
    
    /* Ukuran Tombol Approve agar seragam */
    .btn-sm { font-size: 0.75rem; border-radius: 6px; }
    .text-xs { font-size: 0.7rem; }
</style>
@endsection