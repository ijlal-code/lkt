@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0 text-dark">
                Dokumen: {{ $sp2a->nomor_sp2a ?? 'Menunggu Nomor (Final Approve GM)' }}
            </h3>
            <p class="text-muted small mb-0">ID Transaksi: #{{ $sp2a->id }} | Tahap Saat Ini: <span class="badge bg-primary text-uppercase">{{ $sp2a->current_step }}</span></p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if($sp2a->status == 'Approved By System')
                <a href="{{ route('pesan.show', $sp2a->id) }}?download=true" class="btn btn-outline-danger shadow-sm flex-grow-1">
                    <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download PDF
                </a>
            @endif
            <a href="{{ route('pesan.index') }}" class="btn btn-secondary shadow-sm flex-grow-1">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm border-top border-4 border-primary rounded-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-dark">Panel Persetujuan</h5>

                    <div class="mb-4 text-center">
                        @if($sp2a->status == 'Approved By System')
                            <div class="p-3 bg-success-subtle rounded-3 border border-success-subtle text-success">
                                <i class="bi bi-patch-check-fill fs-2 mb-2"></i>
                                <div class="fw-bold">DISETUJUI OLEH GM</div>
                                <small class="d-block mt-1">{{ \Carbon\Carbon::parse($sp2a->approved_gm_at)->format('d M Y H:i') }}</small>
                            </div>
                        @elseif($sp2a->status == 'Ditolak/Perlu Koreksi')
                            <div class="p-3 bg-danger-subtle rounded-3 border border-danger-subtle text-danger">
                                <i class="bi bi-exclamation-octagon-fill fs-2 mb-2"></i>
                                <div class="fw-bold">PERLU KOREKSI</div>
                            </div>
                        @else
                            <div class="p-3 bg-warning-subtle rounded-3 border border-warning-subtle text-warning-emphasis">
                                <i class="bi bi-hourglass-split fs-2 mb-2"></i>
                                <div class="fw-bold text-uppercase">MENUNGGU {{ $sp2a->current_step }}</div>
                            </div>
                        @endif
                    </div>

                    @if($sp2a->catatan_koreksi)
                        <div class="alert alert-warning border-0 shadow-sm">
                            <h6 class="fw-bold small text-uppercase mb-2"><i class="bi bi-chat-left-text me-1"></i> Catatan Koreksi Terakhir:</h6>
                            <p class="mb-0 small italic">"{{ $sp2a->catatan_koreksi }}"</p>
                        </div>
                    @endif

                    <hr class="my-4">

                    @auth
                        @php
                            $userRole = Auth::user()->role;
                            $currentStep = $sp2a->current_step;
                            $canApprove = false;

                            if($userRole == 'sm' && $currentStep == 'sm') $canApprove = true;
                            if($userRole == 'smqa' && $currentStep == 'smqa') $canApprove = true;
                            if($userRole == 'gm' && $currentStep == 'gm') $canApprove = true;
                        @endphp

                        @if($canApprove)
                            <form action="{{ route('pesan.approve', $sp2a->id) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui dokumen ini?')" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                                    <i class="bi bi-check-lg me-1"></i> SETUJUI DOKUMEN
                                </button>
                            </form>

                            <button class="btn btn-outline-warning w-100 py-2 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKoreksi">
                                <i class="bi bi-pencil-square me-1"></i> KIRIM KOREKSI (REVISI)
                            </button>

                            <div class="collapse mt-3" id="collapseKoreksi">
                                <div class="card card-body bg-light border-warning">
                                    <form action="{{ route('pesan.approve', $sp2a->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="koreksi" value="true">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Detail Perbaikan:</label>
                                            <textarea name="catatan_koreksi" class="form-control" rows="3" placeholder="Tulis instruksi perbaikan untuk Staff..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100 shadow-sm">Kirim ke Staff</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            @if($sp2a->status != 'Approved By System')
                                <div class="text-center py-2">
                                    <span class="badge bg-secondary p-2 w-100">Anda Tidak Memiliki Akses Approval di Tahap Ini</span>
                                </div>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 rounded-3 d-none d-lg-block">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Pengirim</h6>
                    <p class="mb-1 small text-muted">Nama: <span class="text-dark fw-bold d-block">{{ $sp2a->dari_nama }}</span></p>
                    <p class="mb-0 small text-muted">Perihal: <span class="text-dark d-block">{{ $sp2a->perihal }}</span></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden position-relative" style="height: 85vh; min-height: 500px;">
                <div id="pdf-loader" class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small text-uppercase fw-bold">Memuat Dokumen...</p>
                </div>
                
                <iframe 
                    src="{{ route('pesan.show', $sp2a->id) }}" 
                    width="100%" 
                    height="100%" 
                    style="border: none; position: relative; z-index: 2;"
                    onload="document.getElementById('pdf-loader').style.display='none';"
                ></iframe>
            </div>
            <p class="small text-muted text-center mt-2 d-md-none"><i class="bi bi-info-circle me-1"></i> Gunakan dua jari untuk zoom pada mobile.</p>
        </div>
    </div>
</div>

<style>
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-warning-subtle { background-color: #fff3cd !important; }
    .bg-danger-subtle { background-color: #f8d7da !important; }
    .bg-primary-subtle { background-color: #cfe2ff !important; }
    iframe { background-color: #f1f1f1; }
</style>
@endsection