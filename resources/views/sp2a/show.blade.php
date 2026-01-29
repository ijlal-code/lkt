@extends('layouts.app')

@section('content')
<div class="container pb-5">
    
    {{-- HEADER STATUS & NAVIGASI --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Detail SP2A</h4>
            <span class="badge {{ $sp2a->current_step == 'finished' ? 'bg-success' : 'bg-warning text-dark' }} fs-6">
                Status: {{ $sp2a->status }}
            </span>
        </div>
        {{-- TOMBOL KEMBALI --}}
            <a href="{{ route('sp2a.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            
            {{-- TOMBOL DOWNLOAD PDF --}}
            <a href="{{ route('sp2a.download', $sp2a->id) }}" class="btn btn-danger shadow-sm">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
            </a>
    </div>

    {{-- ALERT KOREKSI --}}
    @if($sp2a->current_step == 'staff' && $sp2a->catatan_koreksi)
    <div class="alert alert-danger border-danger shadow-sm">
        <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Perlu Perbaikan!</h5>
        <p class="mb-0">Catatan dari atasan: <em>"{{ $sp2a->catatan_koreksi }}"</em></p>
        <hr>
        <a href="{{ route('sp2a.edit', $sp2a->id) }}" class="btn btn-danger btn-sm">Edit & Perbaiki Dokumen</a>
    </div>
    @endif

    {{-- KERTAS KERJA SURAT --}}
    <div class="card shadow-lg border-0 mb-5">
        <div class="card-body p-5" style="min-height: 800px; font-family: 'Times New Roman', serif; position: relative;">
            
            {{-- KOP SURAT --}}
            <table class="w-100 mb-4 border-bottom border-3 border-dark pb-2">
                <tr>
                    <td width="15%"><img src="{{ asset('img/logo-tonasa.png') }}" width="80"></td>
                    <td class="text-center">
                        <h4 class="fw-bold text-uppercase mb-0">PT Semen Tonasa</h4>
                        <span class="small">Unit Internal Audit</span>
                    </td>
                    <td width="15%" class="text-end"><img src="{{ asset('img/logo-internal-audit.png') }}" width="80"></td>
                </tr>
            </table>

            {{-- JUDUL & NOMOR --}}
            <div class="text-center mb-4">
                <h5 class="fw-bold text-decoration-underline">SURAT PERINGATAN 2A</h5>
                @if($sp2a->current_step == 'finished')
                    <p class="fw-bold mb-0">No: {{ $sp2a->nomor_sp2a }}</p>
                @else
                    <p class="text-danger fst-italic mb-0">[Nomor akan muncul otomatis setelah Approval GM]</p>
                @endif
            </div>

            {{-- META SURAT --}}
            <div class="row mb-3">
                <div class="col-2">Kepada</div>
                <div class="col-10">: <strong>{{ $sp2a->kepada_nama }}</strong></div>
            </div>
            <div class="row mb-3">
                <div class="col-2">Dari</div>
                <div class="col-10">: {{ $sp2a->dari_nama }}</div>
            </div>
            <div class="row mb-4">
                <div class="col-2">Perihal</div>
                <div class="col-10">: {{ $sp2a->perihal }}</div>
            </div>

            {{-- ISI SURAT --}}
            <div class="content-surat text-justify mb-5">
                {!! $sp2a->isi_surat !!}
            </div>

            {{-- TANDA TANGAN (BAGIAN YANG DIUBAH) --}}
            <div class="row mt-5">
                <div class="col-6 offset-6 text-center">
                    <p class="mb-4">Pangkep, {{ $sp2a->tanggal_surat->format('d F Y') }}<br>Hormat Kami,</p>
                    
                    {{-- Area Tanda Tangan: Tinggi fix agar layout tidak lompat --}}
                    <div style="height: 80px; display: flex; flex-direction: column; justify-content: flex-end; align-items: center;">
                        @if($sp2a->current_step == 'finished')
                            {{-- TAMPILAN BARU: TEKS HITAM TANPA KOTAK --}}
                            <div class="text-center">
                                <span class="fw-bold text-uppercase text-dark d-block">APPROVED BY SYSTEM</span>
                            
                            </div>
                        @else
                            {{-- Placeholder jika belum approve --}}
                            <span class="text-muted fst-italic small">[Menunggu Tanda Tangan GM]</span>
                        @endif
                    </div>

                    {{-- NAMA GM (Persis di bawah APPROVED BY SYSTEM) --}}
                    <p class="fw-bold mt-1 text-decoration-underline">{{ $sp2a->penanda_tangan_nama }}</p>
                    <p class="mt-0">GM Internal Audit</p>
                </div>
            </div>

             {{-- TEMBUSAN --}}
             @if(!empty($sp2a->tembusan) && count($sp2a->tembusan) > 0)
             <div class="mt-5 text-start">
                 <p class="mb-1 fw-bold text-decoration-underline">Tembusan:</p>
                 <ol class="ps-3 mb-0">
                     @foreach($sp2a->tembusan as $cc)
                         <li>{{ $cc }}</li>
                     @endforeach
                 </ol>
             </div>
             @endif
        </div>
    </div>

    {{-- PANEL AKSI (Hanya muncul jika User berhak approve di tahap ini) --}}
    @php
        $canApprove = false;
        if(Auth::user()->role == 'sm' && $sp2a->current_step == 'sm') $canApprove = true;
        if(Auth::user()->role == 'smqa' && $sp2a->current_step == 'smqa') $canApprove = true;
        if(Auth::user()->role == 'gm' && $sp2a->current_step == 'gm') $canApprove = true;
    @endphp

    @if($canApprove)
    <div class="card fixed-bottom shadow-lg border-top border-primary">
        <div class="card-body container d-flex justify-content-between align-items-center py-3">
            <div>
                <h6 class="mb-0 fw-bold text-primary">Aksi Diperlukan:</h6>
                <small>Login sebagai <strong>{{ strtoupper(Auth::user()->role) }}</strong>. Silakan periksa dokumen.</small>
            </div>
            <div class="d-flex gap-2">
                {{-- Tombol Koreksi --}}
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalKoreksi">
                    <i class="bi bi-x-circle"></i> Koreksi / Kembalikan
                </button>
                
                {{-- Tombol Approve --}}
                <form action="{{ route('sp2a.approve', $sp2a->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menyetujui dokumen ini?');">
                    @csrf
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="bi bi-check-circle-fill"></i> Setujui & Lanjut
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Koreksi --}}
    <div class="modal fade" id="modalKoreksi" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sp2a.koreksi', $sp2a->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Kirim Koreksi ke Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Perbaikan:</label>
                            <textarea name="catatan" class="form-control" rows="4" required placeholder="Tuliskan apa yang salah..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Kirim Koreksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection