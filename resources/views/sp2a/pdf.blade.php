<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'DRAFT' }}</title>

    <style>
        /* ===============================
           PAGE SETUP – F4 / FOLIO
           =============================== */
        @page {
            size: 215mm 330mm;
            margin: 20mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.6;
        }

        /* ===============================
           UTILITIES
           =============================== */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .fw-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .mb-4 { margin-bottom: 20px; }
        .mb-5 { margin-bottom: 30px; }

        /* ===============================
           KOP SURAT
           =============================== */
        table.kop {
            width: 100%;
            border-bottom: 3px solid black;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        table.kop td {
            vertical-align: middle;
            padding-bottom: 10px;
        }

        /* ===============================
           META SURAT
           =============================== */
        .meta {
            font-size: 12pt;
            line-height: 1.6;
            margin-bottom: 35px;
        }
        .meta-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .meta-label {
            display: table-cell;
            width: 17%;
        }
        .meta-sep {
            display: table-cell;
            width: 3%;
        }
        .meta-val {
            display: table-cell;
            width: 80%;
        }

        /* ===============================
           ISI SURAT
           =============================== */
        .isi-surat {
            text-align: justify;
            margin-bottom: 40px;
        }
        .isi-surat p {
            margin: 0 0 10px 0;
        }
        .isi-surat table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }
        .isi-surat th,
        .isi-surat td {
            border-top: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            padding: 6px;
            vertical-align: top;
        }

        /* ===============================
           TANDA TANGAN
           =============================== */
        table.ttd {
            width: 100%;
            margin-top: 45px;
        }

        /* ===============================
           TEMBUSAN & FOOTER
           =============================== */
        .tembusan {
            margin-top: 30px;
        }
        .footer {
            margin-top: 45px;
            padding-top: 12px;
            border-top: 1px solid #777;
            font-size: 9pt;
            font-style: italic;
            color: #555;
        }

        /* DomPDF safety */
        table, tr, td, th {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

{{-- ===============================
   KOP SURAT
   =============================== --}}
<table class="kop">
    <tr>
        <td width="20%">
            <img src="{{ public_path('img/logo-sig.png') }}" style="width:120px;">
        </td>
        <td class="text-center">
            <div class="fw-bold text-uppercase" style="font-size:15pt;">
                PT SEMEN TONASA
            </div>
            <div class="fw-bold" style="font-size:12pt;">
                UNIT INTERNAL AUDIT
            </div>
        </td>
        <td width="20%" class="text-right">
            <img src="{{ public_path('img/logo-tonasa.png') }}" style="width:80px;">
        </td>
    </tr>
</table>

{{-- ===============================
   JUDUL
   =============================== --}}
<div class="text-center mb-5">
    <div class="fw-bold underline text-uppercase"
         style="font-size:14pt; letter-spacing:0.5px;">
        SURAT PERINTAH PELAKSANAAN AUDIT (SP2A)
    </div>
</div>

{{-- ===============================
   META SURAT
   =============================== --}}
<div class="meta">

    {{-- KEPADA --}}
    <div class="meta-row">
        <div class="meta-label">Kepada Yth.</div>
        <div class="meta-sep">:</div>
        <div class="meta-val">
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
    <div class="meta-row">
        <div class="meta-label">Dari</div>
        <div class="meta-sep">:</div>
        <div class="meta-val">{{ $sp2a->dari_nama }}</div>
    </div>

    {{-- NOMOR --}}
    <div class="meta-row">
        <div class="meta-label">Nomor</div>
        <div class="meta-sep">:</div>
        <div class="meta-val">
            @if($sp2a->current_step == 'finished')
                {{ $sp2a->nomor_sp2a }}
            @else
                <em>/SP2A/PW.00/11.00/07-2025</em>
            @endif
        </div>
    </div>

    {{-- LAMPIRAN --}}
    <div class="meta-row">
        <div class="meta-label">Lampiran</div>
        <div class="meta-sep">:</div>
        <div class="meta-val">
            {{ $sp2a->lampiran ?? '1 (satu) berkas' }}
        </div>
    </div>

    {{-- PERIHAL --}}
    <div class="meta-row">
        <div class="meta-label">Perihal</div>
        <div class="meta-sep">:</div>
        <div class="meta-val fw-bold">
            {{ $sp2a->perihal }}
        </div>
    </div>

</div>

{{-- ===============================
   ISI SURAT
   =============================== --}}
<div class="isi-surat">
    {!! $sp2a->isi_surat !!}
</div>

{{-- ===============================
   TANDA TANGAN
   =============================== --}}
<table class="ttd">
    <tr>
        <td width="55%"></td>
        <td width="45%">
            <p>
                Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}<br>
                Hormat Kami,
            </p>

            <div style="margin:40px 0 6px 0;">
                @if($sp2a->current_step == 'finished')
                    <span class="fw-bold text-uppercase">
                        APPROVED BY SYSTEM
                    </span>
                @else
                    <em>[Menunggu Approval GM]</em>
                @endif
            </div>

            <p class="fw-bold underline" style="margin-bottom:0;">
                {{ $sp2a->penanda_tangan_nama }}
            </p>
            <p>GM Internal Audit</p>
        </td>
    </tr>
</table>

{{-- ===============================
   TEMBUSAN
   =============================== --}}
@if(!empty($sp2a->tembusan))
    <div class="tembusan">
        <p class="fw-bold underline mb-0">Cc:</p>
        <ol>
            @foreach($sp2a->tembusan as $cc)
                <li>{{ $cc }}</li>
            @endforeach
        </ol>
    </div>
@endif

{{-- ===============================
   FOOTER
   =============================== --}}
<div class="footer">
    Dokumen ini telah ditandatangani secara elektronik.<br>
    ID Dokumen: {{ $sp2a->nomor_sp2a ?? 'DRAFT-'.$sp2a->id }} |
    Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
</div>

</body>
</html>
