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
                <tr>
                    <td width="15%">Kepada Yth.</td>
                    <td width="2%">:</td>
                    <td>{!! nl2br(e($sp2a->kepada_nama)) !!}</td>
                </tr>
                <tr>
                    <td>Dari</td>
                    <td>:</td>
                    <td>{{ $sp2a->dari_nama }}</td>
                </tr>
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td>
                        @if($sp2a->current_step == 'finished')
                            {{ $sp2a->nomor_sp2a }}
                        @else
                            <span class="fst-italic text-muted">
                                /SP2A/PW.00/11.00/07-2025
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>{{ $sp2a->lampiran ?? '1 (satu) berkas' }}</td>
                </tr>
                <tr>
                    <td class="align-top">Perihal</td>
                    <td class="align-top">:</td>
                    <td class="fw-bold">{{ $sp2a->perihal }}</td>
                </tr>
            </table>

            {{-- ===================== --}}
            {{-- ISI SURAT (TIDAK DIUBAH) --}}
            {{-- ===================== --}}
            <div class="content-surat text-justify mb-5">
                {!! $sp2a->isi_surat !!}
            </div>

            {{-- TANDA TANGAN --}}
            <div class="row mt-5">
                <div class="col-md-6">
                    <p>Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</p>
                    <p>Hormat Kami,</p>

                    <div style="margin:20px 0;">
                        @if($sp2a->current_step == 'finished')
                            <strong>APPROVED BY SYSTEM</strong>
                        @else
                            <em class="text-muted">[Menunggu Approval GM]</em>
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
