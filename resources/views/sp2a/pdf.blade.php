<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'DRAFT' }}</title>
    <style>
        /* SETUP HALAMAN */
        @page {
            margin: 1cm 2cm; /* Atur margin kertas saat diprint */
        }
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        
        /* CONTAINER */
        .container {
            width: 100%;
        }

        /* --- KOP SURAT --- */
        table.kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 25px;
        }
        .kop-title {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin: 0;
        }
        .kop-subtitle {
            font-size: 12pt;
            text-align: center;
            margin: 0;
        }

        /* --- JUDUL & NOMOR --- */
        .judul-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .judul-main {
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin: 0;
        }
        .nomor-surat {
            font-weight: bold;
            margin-top: 5px;
        }

        /* --- META INFO --- */
        table.meta-info {
            width: 100%;
            margin-bottom: 20px;
        }
        table.meta-info td {
            vertical-align: top;
            padding: 2px 0;
        }
        .label { width: 100px; } /* Lebar label 'Kepada', 'Dari' */
        .separator { width: 20px; text-align: center; }

        /* --- ISI SURAT --- */
        .isi-surat {
            text-align: justify;
            margin-bottom: 40px;
            min-height: 150px;
        }

        /* --- TANDA TANGAN (POSISI KIRI) --- */
        .signature-wrapper {
            margin-top: 30px;
            text-align: left; /* Pastikan rata kiri */
            width: 50%; /* Mengambil setengah halaman sebelah kiri */
        }
        
        .status-box {
            margin: 15px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* --- CC & FOOTER BAWAH --- */
        .cc-section {
            margin-top: 40px;
            font-size: 11pt;
        }
        .cc-label {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .cc-list {
            margin-top: 0;
            padding-left: 20px;
        }
        
        /* TEXT OTOMATIS DI BAWAH CC */
        .footer-note {
            margin-top: 20px;
            font-size: 9pt;
            color: #555;
            font-style: italic;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            width: 100%;
        }

        /* Utilities */
        .fw-bold { font-weight: bold; }
        .text-underline { text-decoration: underline; }
        .text-danger { color: red; font-style: italic; }
    </style>
</head>
<body>

    <div class="container">
        
        {{-- 1. KOP SURAT --}}
        <table class="kop-surat">
            <tr>
                <td width="15%">
                    {{-- Gunakan public_path agar terbaca oleh DomPDF --}}
                    <img src="{{ public_path('img/logo-tonasa.png') }}" width="70" alt="Logo Tonasa">
                </td>
                <td>
                    <h2 class="kop-title">PT Semen Tonasa</h2>
                    <p class="kop-subtitle">Unit Internal Audit</p>
                </td>
                <td width="15%" style="text-align: right;">
                    <img src="{{ public_path('img/logo-internal-audit.png') }}" width="70" alt="Logo IA">
                </td>
            </tr>
        </table>

        {{-- 2. JUDUL & NOMOR --}}
        <div class="judul-surat">
            <h3 class="judul-main">SURAT PERINGATAN 2A</h3>
            
            @if($sp2a->current_step == 'finished')
                <p class="nomor-surat">No: {{ $sp2a->nomor_sp2a }}</p>
            @else
                <p class="nomor-surat text-danger">[Nomor Belum Terbit - DRAFT]</p>
            @endif
        </div>

        {{-- 3. META INFO --}}
        <table class="meta-info">
            <tr>
                <td class="label">Kepada</td>
                <td class="separator">:</td>
                <td><strong>{{ $sp2a->kepada_nama }}</strong></td>
            </tr>
            <tr>
                <td class="label">Dari</td>
                <td class="separator">:</td>
                <td>{{ $sp2a->dari_nama }}</td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td class="separator">:</td>
                <td>{{ $sp2a->perihal }}</td>
            </tr>
        </table>

        {{-- 4. ISI SURAT --}}
        <div class="isi-surat">
            {!! $sp2a->isi_surat !!}
        </div>

        {{-- 5. TANDA TANGAN (SEBELAH KIRI) --}}
        <div class="signature-wrapper">
            {{-- Tanggal Otomatis (Format Indonesia) --}}
            <p style="margin-bottom: 0;">
                Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}
            </p>
            <p style="margin-top: 5px;">Hormat Kami,</p>

            {{-- Status Approved --}}
            <div class="status-box">
                @if($sp2a->current_step == 'finished')
                    APPROVED BY SYSTEM
                @else
                    <span style="color: #aaa; font-style: italic; font-weight: normal; font-size: 10pt;">
                        [Menunggu Approval GM]
                    </span>
                @endif
            </div>

            {{-- Nama GM --}}
            <p class="fw-bold text-underline" style="margin-bottom: 0;">{{ $sp2a->penanda_tangan_nama }}</p>
            <p style="margin-top: 2px;">GM Internal Audit</p>
        </div>

        {{-- 6. CC (TEMBUSAN) --}}
        @if(!empty($sp2a->tembusan) && count($sp2a->tembusan) > 0)
        <div class="cc-section">
            <div class="cc-label">Cc:</div>
            <ol class="cc-list">
                @foreach($sp2a->tembusan as $cc)
                    <li>{{ $cc }}</li>
                @endforeach
            </ol>
        </div>
        @endif

        {{-- 7. FOOTER TEKS OTOMATIS (Di Bawah CC) --}}
        <div class="footer-note">
            {{-- Contoh teks otomatis, silakan sesuaikan dengan teks di foto Anda --}}
            Dokumen ini telah ditandatangani secara elektronik. <br>
            ID Dokumen: {{ $sp2a->nomor_sp2a ?? 'DRAFT-'.$sp2a->id }} | Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
        </div>

    </div>

</body>
</html>