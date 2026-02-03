<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'DRAFT' }}</title>

    <style>
        /* =================================================
           PAGE SETUP — F4 ASLI
           ================================================= */
        @page {
            size: 210mm 330mm;   /* F4 */
            margin: 15mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
        }

        /* =================================================
           CONTAINER UTAMA (BIAR ISI TIDAK KELUAR KERTAS)
           ================================================= */
        .pdf-wrapper {
            width: 180mm;            /* lebar efektif F4 */
            margin: 0 auto;
        }

        /* =================================================
           KOP & META (JIKA ADA)
           ================================================= */
        table {
            border-collapse: collapse;
        }

        /* =================================================
           ISI SURAT (INTI)
           ================================================= */
        .isi-surat {
            width: 100%;
        }

        .isi-surat p {
            margin: 0 0 8px 0;
            text-align: justify;
        }

        .isi-surat table {
            width: 100%;
            margin: 10px 0;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .isi-surat th,
        .isi-surat td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10pt;
            vertical-align: top;
            word-wrap: break-word;
        }

        .isi-surat th {
            background-color: #E6E6E6;
            font-weight: bold;
            text-align: center;
        }

        .isi-surat ul,
        .isi-surat ol {
            margin: 6px 0 6px 18px;
            padding: 0;
        }

        .isi-surat li {
            margin-bottom: 4px;
        }

        /* =================================================
           PAGE BREAK AMAN
           ================================================= */
        table, tr, td, th {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

<div class="pdf-wrapper">

    {{-- =================================================
       KOP SURAT + META
       (jika di show ada, pastikan sama)
       ================================================= --}}
    @includeIf('sp2a.header')

    {{-- =================================================
       ISI SURAT — SAMA PERSIS DENGAN SHOW
       ================================================= --}}
    <div class="isi-surat">
        {!! $sp2a->isi_surat !!}
    </div>

</div>

</body>
</html>
