<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'DRAFT' }}</title>

    <style>
        /* =======================
           SETUP KERTAS F4
        ======================== */
        @page {
            size: 8.5in 13in;
            margin: 0.5in;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.2;
            color: #000;
        }

        /* =======================
           UTILITIES
        ======================== */
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .text-uppercase { text-transform: uppercase; }
        .text-decoration-underline { text-decoration: underline; }
        .mb-0 { margin-bottom: 0; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 3rem; }
        .mt-5 { margin-top: 3rem; }
        .small { font-size: 0.875em; }
        .text-muted { color: #6c757d; }
        .fst-italic { font-style: italic; }
        .clearfix::after { content: ""; display: table; clear: both; }

        /* =======================
           KOP SURAT
        ======================== */
        table.kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
        }

        /* =======================
           META INFO
        ======================== */
        table.meta-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.meta-info td {
            padding-bottom: 4px;
            vertical-align: top;
        }

        .col-label { width: 17%; }
        .col-sep   { width: 3%; text-align: center; }
        .col-val   { width: 80%; }

        /* =======================
           ISI SURAT
        ======================== */
        .content-surat {
            text-align: justify;
            line-height: 1.3;
        }

        /* =======================
           TABEL DOMPDF FINAL FIX
        ======================== */
        .content-surat table {
            width: 7.47in;              /* TOTAL LEBAR KOLOM */
            margin: 0 auto 12px auto;   /* CENTER */
            border-collapse: collapse;
            table-layout: fixed;        /* WAJIB */
        }

        .content-surat th,
        .content-surat td {
            border: 1px solid #000;
            padding: 2px 4px;
            font-size: 10pt;
            vertical-align: middle;
            word-wrap: break-word;
            height: 0.34in;             /* TINGGI BARIS */
            line-height: 1.1;
        }

        /* Bersihkan margin editor */
        .content-surat table p {
            margin: 0;
            padding: 0;
        }

        /* =======================
           TANDA TANGAN
        ======================== */
        .signature-container {
            width: 45%;
            float: right;
            margin-top: 2rem;
            page-break-inside: avoid;
        }

        /* =======================
           FOOTER
        ======================== */
        .footer-note {
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #6c757d;
            font-size: 0.8rem;
            font-style: italic;
            color: #6c757d;
        }
    </style>
</head>

<body>

{{-- =======================
     KOP SURAT
======================= --}}
<table class="kop-surat">
    <tr>
        <td width="20%">
            <img src="{{ public_path('img/logo-sig.png') }}" style="width:100px">
        </td>
        <td class="text-center">
            <h4 class="fw-bold mb-0 text-uppercase" style="font-size:16pt;">PT SEMEN TONASA</h4>
            <span class="fw-bold" style="font-size:12pt;">UNIT INTERNAL AUDIT</span>
        </td>
        <td width="20%" class="text-end">
            <img src="{{ public_path('img/logo-tonasa.png') }}" style="width:60px">
        </td>
    </tr>
</table>

{{-- =======================
     JUDUL
======================= --}}
<div class="text-center mb-4">
    <h5 class="fw-bold text-uppercase text-decoration-underline" style="font-size:14pt;">
        SURAT PERINTAH PELAKSANAAN AUDIT (SP2A)
    </h5>
</div>

{{-- =======================
     META INFO
======================= --}}
<table class="meta-info">
    <tr>
        <td class="col-label">Kepada Yth.</td>
        <td class="col-sep">:</td>
        <td class="col-val">
            @foreach((array) $sp2a->kepada_nama as $k)
                <div>{{ $k }}</div>
            @endforeach
        </td>
    </tr>
    <tr>
        <td class="col-label">Dari</td>
        <td class="col-sep">:</td>
        <td class="col-val">{{ $sp2a->dari_nama }}</td>
    </tr>
    <tr>
        <td class="col-label">Nomor</td>
        <td class="col-sep">:</td>
        <td class="col-val">
            {{ $sp2a->nomor_sp2a ?? '/SP2A/PW.00/11.00/07-2025' }}
        </td>
    </tr>
    <tr>
        <td class="col-label">Lampiran</td>
        <td class="col-sep">:</td>
        <td class="col-val">{{ $sp2a->lampiran ?? '1 (satu) berkas' }}</td>
    </tr>
    <tr>
        <td class="col-label">Perihal</td>
        <td class="col-sep">:</td>
        <td class="col-val fw-bold">{{ $sp2a->perihal }}</td>
    </tr>
</table>

{{-- =======================
     ISI SURAT + TABEL
======================= --}}
<div class="content-surat mb-5">

<table>
    <!-- COLGROUP WAJIB -->
    <colgroup>
        <col style="width:0.40in">
        <col style="width:1.22in">
        <col style="width:1.22in">
        <col style="width:3.18in">
        <col style="width:1.45in">
    </colgroup>

    <thead>
        <tr>
            <th>NO</th>
            <th>TANGGAL / JAM</th>
            <th>KEGIATAN / AUDITEE</th>
            <th>RUANG LINGKUP</th>
            <th>AUDITOR</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>1</td>
            <td>22 Juli 2025</td>
            <td>Opening Meeting</td>
            <td>All</td>
            <td>All</td>
        </tr>
    </tbody>
</table>

{!! $sp2a->isi_surat !!}

</div>

{{-- =======================
     TANDA TANGAN
======================= --}}
<div class="clearfix">
    <div class="signature-container">
        <p>Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</p>
        <p>Hormat Kami,</p>

        <div style="margin:40px 0 5px;">
            <span class="fw-bold" style="border:1px solid #000; padding:5px;">
                APPROVED BY SYSTEM
            </span>
        </div>

        <p class="fw-bold text-decoration-underline mb-0">
            {{ $sp2a->penanda_tangan_nama }}
        </p>
        <p>GM Internal Audit</p>
    </div>
</div>

{{-- =======================
     FOOTER
======================= --}}
<div class="footer-note">
    Dokumen ditandatangani secara elektronik<br>
    ID Dokumen: {{ $sp2a->nomor_sp2a ?? 'DRAFT-'.$sp2a->id }} |
    Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
</div>

</body>
</html>
