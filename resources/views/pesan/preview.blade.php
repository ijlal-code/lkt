@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Dokumen: {{ $sp2a->nomor_sp2a }}</h3>
            <p class="text-muted small">ID Transaksi: #{{ $sp2a->id }}</p>
        </div>
        <div class="d-flex gap-2">
    <a href="{{ route('pesan.show', $sp2a->id) }}?download=true" class="btn btn-outline-danger shadow-sm">
        <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download PDF
    </a>
    <a href="{{ route('pesan.index') }}" class="btn btn-secondary shadow-sm">Kembali</a>
</div>
    </div>

    <div class="row g-4">
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm border-top border-4 border-primary rounded-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Status Verifikasi</h5>
                    
                    <div class="mb-4 text-center">
                        @if($sp2a->status == 'Approved By System')
                            <div class="p-3 bg-success-subtle rounded border border-success-subtle">
                                <i class="bi bi-patch-check-fill text-success fs-2 mb-2"></i>
                                <div class="fw-bold text-success">{{ $sp2a->status }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($sp2a->approved_at)->format('d M Y H:i') }}</small>
                            </div>
                        @else
                            <div class="p-3 bg-warning-subtle rounded border border-warning-subtle">
                                <i class="bi bi-hourglass-split text-warning fs-2 mb-2"></i>
                                <div class="fw-bold text-warning-emphasis">Menunggu Persetujuan</div>
                            </div>
                        @endif
                    </div>

                    <hr>

                    @if(Auth::user()->role == 'k3' && $sp2a->status != 'Approved By System')
                        <form action="{{ route('pesan.approve', $sp2a->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Bubuhkan tanda tangan digital pada dokumen ini?')" class="btn btn-success w-100 py-3 fw-bold shadow-lg">
                                ✅ APPROVE DOKUMEN
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4 rounded-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Metadata Dokumen</h6>
                    <small class="text-muted d-block">Pengirim:</small>
                    <p class="mb-2 fw-medium text-dark">{{ $sp2a->dari_nama }}</p>
                    <small class="text-muted d-block">Perihal:</small>
                    <p class="mb-0 text-dark">{{ $sp2a->perihal }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm overflow-hidden" style="height: 85vh;">
                <div class="card-body p-0">
                    <iframe src="{{ route('pesan.show', $sp2a->id) }}" width="100%" height="100%" style="border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection