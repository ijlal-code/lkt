@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-0 text-dark">Dokumen: {{ $sp2a->nomor_sp2a }}</h3>
            <p class="text-muted small mb-0">ID Transaksi: #{{ $sp2a->id }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('pesan.show', $sp2a->id) }}?download=true" class="btn btn-outline-danger flex-grow-1 flex-md-grow-0 shadow-sm">
                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download PDF
            </a>
            <a href="{{ route('pesan.index') }}" class="btn btn-secondary flex-grow-1 flex-md-grow-0 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm border-top border-4 border-primary rounded-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-dark">Status Verifikasi</h5>
                    
                    <div class="mb-4 text-center">
                        @if($sp2a->status == 'Approved By System')
                            <div class="p-3 bg-success-subtle rounded-3 border border-success-subtle">
                                <i class="bi bi-patch-check-fill text-success fs-2 mb-2"></i>
                                <div class="fw-bold text-success">{{ $sp2a->status }}</div>
                                <small class="text-muted d-block mt-1">
                                    {{ \Carbon\Carbon::parse($sp2a->approved_at)->format('d M Y H:i') }}
                                </small>
                            </div>
                        @else
                            <div class="p-3 bg-warning-subtle rounded-3 border border-warning-subtle">
                                <i class="bi bi-hourglass-split text-warning fs-2 mb-2"></i>
                                <div class="fw-bold text-warning-emphasis">Menunggu Persetujuan</div>
                            </div>
                        @endif
                    </div>

                    @if(Auth::user()->role == 'k3' && $sp2a->status != 'Approved By System')
                        <hr class="my-4">
                        <form action="{{ route('pesan.approve', $sp2a->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Bubuhkan tanda tangan digital pada dokumen ini?')" class="btn btn-success w-100 py-3 fw-bold shadow">
                                APPROVE DOKUMEN
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 rounded-3 d-none d-lg-block">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-dark border-bottom pb-2">Metadata Dokumen</h6>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 10px;">Pengirim</small>
                        <p class="mb-0 fw-medium text-dark">{{ $sp2a->dari_nama }}</p>
                    </div>
                    <div>
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 10px;">Perihal</small>
                        <p class="mb-0 text-dark small">{{ $sp2a->perihal }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden position-relative" style="height: 85vh; min-height: 500px;">
                <div id="pdf-loader" class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 1;">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted small">Memuat Dokumen...</p>
                </div>
                
                <iframe 
                    src="{{ route('pesan.show', $sp2a->id) }}" 
                    width="100%" 
                    height="100%" 
                    style="border: none; position: relative; z-index: 2;"
                    onload="document.getElementById('pdf-loader').style.display='none';"
                ></iframe>
            </div>
            
            <div class="d-block d-lg-none mt-3 text-center">
                <p class="small text-muted italic">
                    <i class="bi bi-info-circle me-1"></i> Gunakan dua jari untuk melakukan zoom pada tampilan PDF mobile.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling agar iframe smooth di mobile */
    iframe {
        background-color: #eee;
    }
    .bg-success-subtle { background-color: #d1e7dd !important; }
    .bg-warning-subtle { background-color: #fff3cd !important; }
    
    @media (max-width: 768px) {
        .container-fluid { padding-top: 1rem !important; }
        h3 { font-size: 1.25rem; }
    }
</style>
@endsection