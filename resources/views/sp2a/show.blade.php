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
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('sp2a.download', $sp2a->id) }}" class="btn btn-danger shadow-sm">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download PDF
            </a>
        </div>
    </div>

    {{-- ALERT KOREKSI --}}
    @if($sp2a->current_step == 'staff' && $sp2a->catatan_koreksi)
    <div class="alert alert-danger border-danger shadow-sm">
        <h5 class="fw-bold text-uppercase">{{ $sp2a->status }}</h5>
        <p class="mb-0">
            <strong>Catatan Koreksi:</strong><br>
            <em>"{{ $sp2a->catatan_koreksi }}"</em>
        </p>
        <hr>
        <a href="{{ route('sp2a.edit', $sp2a->id) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-pencil-square"></i> Edit & Perbaiki Dokumen
        </a>
    </div>
    @endif

    {{-- KERTAS SURAT --}}
    <div class="card shadow-lg border-0 mb-5">
        <div class="card-body p-5" style="font-family:'Times New Roman', serif; position:relative;">

            {{-- KOP SURAT --}}
            <table class="w-100 mb-4 border-bottom border-3 border-dark pb-2">
                <tr>
                    <td width="20%">
                        <img src="{{ asset('img/logo-sig.png') }}" style="max-width:120px">
                    </td>
                    <td class="text-center">
                        <h4 class="fw-bold mb-0 text-uppercase">PT SEMEN TONASA</h4>
                        <span class="fw-bold">UNIT INTERNAL AUDIT</span>
                    </td>
                    <td width="20%" class="text-end">
                        <img src="{{ asset('img/logo-tonasa.png') }}" style="max-width:80px">
                    </td>
                </tr>
            </table>

            {{-- ===================== --}}
            {{-- HEADER SESUAI FOTO --}}
            {{-- ===================== --}}

            {{-- JUDUL --}}
            <div class="text-center mb-4">
                <h5 class="fw-bold text-uppercase text-decoration-underline">
                    SURAT PERINTAH PELAKSANAAN AUDIT (SP2A)
                </h5>
            </div>

            {{-- META SURAT (FORMAT RESMI FOTO) --}}
            <table class="w-100 mb-4" style="font-size:15px;">
              {{-- META SURAT (RAPI SEPERTI FOTO) --}}
<div style="font-size:15px;" class="mb-5">

    {{-- KEPADA YTH --}}
    <div style="display:flex; align-items:flex-start; margin-bottom:4px;">
        <div style="width:17%;">Kepada Yth.</div>
        <div style="width:3%;">:</div>
        <div style="flex:1;">
            @php
                $kepadaList = is_array($sp2a->kepada_nama)
                    ? $sp2a->kepada_nama
                    : preg_split('/\r\n|\r|\n/', $sp2a->kepada_nama);
            @endphp

            @foreach($kepadaList as $kepada)
                <div>{{ $kepada }}</div>
            @endforeach
        </div>
    </div>

    {{-- DARI --}}
    <div style="display:flex; align-items:flex-start; margin-bottom:4px;">
        <div style="width:17%;">Dari</div>
        <div style="width:3%;">:</div>
        <div style="flex:1;">{{ $sp2a->dari_nama }}</div>
    </div>

    {{-- NOMOR --}}
    <div style="display:flex; align-items:flex-start; margin-bottom:4px;">
        <div style="width:17%;">Nomor</div>
        <div style="width:3%;">:</div>
        <div style="flex:1;">
            @if($sp2a->current_step == 'finished')
                {{ $sp2a->nomor_sp2a }}
            @else
                <span class="fst-italic text-muted">
                    /SP2A/PW.00/11.00/07-2025
                </span>
            @endif
        </div>
    </div>

    {{-- LAMPIRAN --}}
    <div style="display:flex; align-items:flex-start; margin-bottom:4px;">
        <div style="width:17%;">Lampiran</div>
        <div style="width:3%;">:</div>
        <div style="flex:1;">{{ $sp2a->lampiran ?? '1 (satu) berkas' }}</div>
    </div>

    {{-- PERIHAL --}}
    <div style="display:flex; align-items:flex-start;">
        <div style="width:17%;">Perihal</div>
        <div style="width:3%;">:</div>
        <div style="flex:1; font-weight:bold;">
            {{ $sp2a->perihal }}
        </div>
    </div>

</div>
  

            {{-- ===================== --}}
            {{-- ISI SURAT (TIDAK DIUBAH) --}}
            {{-- ===================== --}}
            <div class="content-surat text-justify mb-5">
                {!! $sp2a->isi_surat !!}
            </div>

            {{-- TANDA TANGAN --}}
            {{-- TANDA TANGAN --}}
            <div class="row mt-5">
                <div class="col-md-6">
                    <p>Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</p>
                    <p>Hormat Kami,</p>

                    {{-- PERBAIKAN: Margin diatur agar dekat dengan nama, font diperkecil --}}
                    <div style="margin-top: 40px; margin-bottom: 5px;">
                        @if($sp2a->current_step == 'finished')
                            <span class="fw-bold text-uppercase" style="font-size: 0.9rem; color: #000;">
                                APPROVED BY SYSTEM
                            </span>
                        @else
                            <em class="text-muted small">[Menunggu Approval GM]</em>
                        @endif
                    </div>

                    <p class="fw-bold text-decoration-underline mb-0">
                        {{ $sp2a->penanda_tangan_nama }}
                    </p>
                    <p>GM Internal Audit</p>
                </div>
            </div>

            {{-- TEMBUSAN --}}
            @if(!empty($sp2a->tembusan))
            <div class="mt-5">
                <p class="fw-bold text-decoration-underline mb-1">Cc:</p>
                <ol class="mb-0">
                    @foreach($sp2a->tembusan as $cc)
                        <li>{{ $cc }}</li>
                    @endforeach
                </ol>
            </div>
            @endif

            {{-- FOOTER --}}
            <div class="mt-5 pt-3 border-top text-muted small fst-italic">
                Dokumen ini telah ditandatangani secara elektronik.<br>
                ID Dokumen: {{ $sp2a->nomor_sp2a ?? 'DRAFT-'.$sp2a->id }} |
                Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
            </div>

        </div>
    </div>

</div>
@endsection
