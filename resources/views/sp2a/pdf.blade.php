<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'DRAFT' }}</title>

    <style>
        /* ===============================
           PAGE SETUP
           =============================== */
        @page {
            size: 230mm 330mm;
            margin: 15mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5; /* Untuk paragraf normal */
            color: #000;
        }

        /* ===============================
           UTILITIES
           =============================== */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .fw-bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .mb-5 { margin-bottom: 30px; }

        /* ===============================
           KOP SURAT & META
           =============================== */
        table.kop {
            width: 100%;
            border-bottom: 3px solid #000;
            border-collapse: collapse;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        table.kop td { border: none; padding-bottom: 10px; vertical-align: middle; }

        table.meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
            font-size: 12pt;
            page-break-inside: avoid;
        }
        table.meta td { border: none; padding: 2px 0; vertical-align: top; }

        .label { width: 17%; }
        .sep { width: 3%; text-align: center; }
        .val { width: 80%; }

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

        /* ===============================
           TABEL (INTI PERBAIKAN)
           =============================== */
        .isi-surat table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 10px 0 20px 0;
            page-break-inside: avoid;
        }

        /* Garis tabel */
        .isi-surat table tr,
        .isi-surat table th,
        .isi-surat table td {
            border: 1px solid #000 !important;
            vertical-align: top;
            font-size: 12pt;
            background-clip: padding-box;
        }

        /* ===============================
           NORMALISASI ISI DALAM TABEL (KUNCI)
           =============================== */

        /* Line height rapat khusus tabel */
        .isi-surat table td,
        .isi-surat table th {
            line-height: 1.2;
            padding: 4px 6px; /* atas-bawah diperkecil */
        }

        /* MATIKAN margin bawaan editor */
        .isi-surat table td p,
        .isi-surat table td div {
            margin: 0;
            padding: 0;
        }

        /* Jika editor pakai <br><br> */
        .isi-surat table td br + br {
            display: none;
        }

        /* List di dalam tabel */
        .isi-surat table td ul,
        .isi-surat table td ol {
            margin: 0;
            padding-left: 16px;
        }

        .isi-surat table td li {
            margin: 0;
            padding: 0;
        }

        /* ===============================
           TTD & FOOTER
           =============================== */
        table.ttd {
            width: 100%;
            margin-top: 45px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        table.ttd td { border: none; }

        .tembusan { margin-top: 30px; page-break-inside: avoid; }

        .footer {
            margin-top: 50px;
            padding-top: 10px;
            border-top: 1px solid #aaa;
            font-size: 9pt;
            font-style: italic;
            color: #555;
        }
    </style>
</head>

<body>

{{-- ================= KOP ================= --}}
<table class="kop">
    <tr>
        <td width="20%">
            <img src="{{ public_path('img/logo-sig.png') }}" style="width:120px;">
        </td>
        <td class="text-center">
            <div class="fw-bold text-uppercase" style="font-size:15pt;">PT SEMEN TONASA</div>
            <div class="fw-bold" style="font-size:12pt;">UNIT INTERNAL AUDIT</div>
        </td>
        <td width="20%" class="text-right">
            <img src="{{ public_path('img/logo-tonasa.png') }}" style="width:80px;">
        </td>
    </tr>
</table>

{{-- ================= JUDUL ================= --}}
<div class="text-center mb-5">
    <div class="fw-bold underline text-uppercase" style="font-size:14pt; letter-spacing:0.5px;">
        SURAT PERINTAH PELAKSANAAN AUDIT (SP2A)
    </div>
</div>

{{-- ================= META ================= --}}
<table class="meta">
    <tr>
        <td class="label">Kepada Yth.</td>
        <td class="sep">:</td>
        <td class="val">
            @php
                $kepadaList = is_array($sp2a->kepada_nama)
                    ? $sp2a->kepada_nama
                    : preg_split('/\r\n|\r|\n/', (string)$sp2a->kepada_nama);
            @endphp
            @foreach($kepadaList as $kepada)
                <div>{{ $kepada }}</div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td class="label">Dari</td>
        <td class="sep">:</td>
        <td class="val">{{ $sp2a->dari_nama }}</td>
    </tr>
    <tr>
        <td class="label">Nomor</td>
        <td class="sep">:</td>
        <td class="val">
            {{ $sp2a->current_step == 'finished'
                ? $sp2a->nomor_sp2a
                : '/SP2A/PW.00/11.00/07-2025' }}
        </td>
    </tr>
    <tr>
        <td class="label">Lampiran</td>
        <td class="sep">:</td>
        <td class="val">{{ $sp2a->lampiran ?? '1 (satu) berkas' }}</td>
    </tr>
    <tr>
        <td class="label">Perihal</td>
        <td class="sep">:</td>
        <td class="val fw-bold">{{ $sp2a->perihal }}</td>
    </tr>
</table>

{{-- ================= ISI SURAT ================= --}}
<div class="isi-surat">
@php
    $content = $sp2a->isi_surat ?? '';

    /* Bersihkan atribut tabel bawaan editor */
    $content = preg_replace(
        '/<table[^>]*>/i',
        '<table border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;width:100%;">',
        $content
    );
@endphp

{!! $content !!}
</div>

{{-- ================= TTD ================= --}}
<table class="ttd">
    <tr>
        <td width="55%"></td>
        <td width="45%">
            <p>
                Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}<br>
                Hormat Kami,
            </p>

            <div style="margin:40px 0 6px 0;">
                {!! $sp2a->current_step == 'finished'
                    ? '<strong>APPROVED BY SYSTEM</strong>'
                    : '<em>[Menunggu Approval GM]</em>' !!}
            </div>

            <p class="fw-bold underline" style="margin-bottom:0;">
                {{ $sp2a->penanda_tangan_nama }}
            </p>
            <p>GM Internal Audit</p>
        </td>
    </tr>
</table>

{{-- ================= TEMBUSAN ================= --}}
@if(!empty($sp2a->tembusan))
<div class="tembusan">
    <p class="fw-bold underline mb-0">Cc:</p>
    <ol style="margin-top:0;">
        @foreach($sp2a->tembusan as $cc)
            <li>{{ $cc }}</li>
        @endforeach
    </ol>
</div>
@endif

{{-- ================= FOOTER ================= --}}
<div class="footer">
    Dokumen ini telah ditandatangani secara elektronik.<br>
    ID Dokumen: {{ $sp2a->nomor_sp2a ?? 'DRAFT-'.$sp2a->id }} |
    Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
</div>

</body>
</html>
