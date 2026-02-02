<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'DRAFT' }}</title>

    <style>
        @page { margin: 2cm 2.5cm; }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.3;
            color: #000;
        }

        .container { width: 100%; }

        /* ================= KOP SURAT ================= */
        table.kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
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
            font-weight: bold; /* Ditebalkan sesuai standar kop */
            text-align: center;
            margin: 0;
        }

        /* ================= JUDUL ================= */
        .judul-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .judul-main {
            font-weight: bold;
            text-decoration: underline;
            font-size: 12pt;
            text-transform: uppercase;
            margin: 0;
        }

        /* ================= META INFO ================= */
        table.meta-info {
            width: 100%;
            margin-bottom: 0px; /* Jarak diatur lewat spacer nanti */
            border-collapse: collapse;
        }
        table.meta-info td {
            vertical-align: top;
            padding: 2px 0;
        }
        .label { width: 17%; } /* Lebar label disesuaikan */
        .separator { width: 3%; text-align: center; }
        .content-meta { width: 80%; }

        /* ================= ISI ================= */
        .spacer {
            height: 30px; /* Jarak pengganti <br> */
        }
        /* ================= ISI SURAT (TABLE FIX) ================= */
        .isi-surat {
            text-align: justify;
            line-height: 1.5;
            width: 100%; /* Pastikan kontainer penuh */
        }
        
        /* CSS KHUSUS TABEL PDF AGAR TIDAK KELUAR KERTAS */
        .isi-surat table {
            width: 100% !important;      /* Paksa lebar tabel mengikuti kertas */
            table-layout: fixed;         /* PENTING: Mencegah tabel melebar otomatis */
            border-collapse: collapse !important;
            border-spacing: 0;
            margin-bottom: 10px;
        }

        .isi-surat table td, 
        .isi-surat table th {
            border: 1px solid #000 !important;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;       /* PENTING: Teks panjang dipaksa turun baris */
            word-break: break-all;       /* Opsional: Memutus kata jika terlalu panjang */
            font-size: 12pt;             /* Samakan ukuran font isi tabel */
        }
        
        /* Penyesuaian gambar dalam tabel jika ada */
        .isi-surat img {
            max-width: 100%;
            height: auto;
        }

.isi-surat td, .isi-surat th {
    /* Ini style default jika warna dari Word gagal terbaca */
    border: 1px solid black; 
    padding: 4px;
}

        /* ================= TTD ================= */
        .signature-wrapper {
            margin-top: 50px;
            width: 50%; /* Tanda tangan biasanya di kiri atau kanan, ini default block */
            page-break-inside: avoid; /* Mencegah TTD terpotong halaman */
        }

        /* ================= CC & FOOTER ================= */
        .cc-section {
            margin-top: 30px;
            font-size: 11pt;
        }
        .cc-label {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .cc-list {
            margin: 0;
            padding-left: 20px;
        }

        .footer-note {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 9pt;
            color: #000;
            font-style: italic;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .fw-bold { font-weight: bold; }
        .text-underline { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">

    {{-- 1. KOP SURAT --}}
    <table class="kop-surat">
        <tr>
            <td width="20%">
                {{-- Menggunakan public_path agar terbaca oleh DomPDF --}}
                <img src="{{ public_path('img/logo-sig.png') }}" style="width: 100px; height: auto;">
            </td>
            <td align="center">
                <div class="kop-title">PT SEMEN TONASA</div>
                <div class="kop-subtitle">UNIT INTERNAL AUDIT</div>
            </td>
            <td width="20%" align="right">
                <img src="{{ public_path('img/logo-tonasa.png') }}" style="width: 70px; height: auto;">
            </td>
        </tr>
    </table>

    {{-- 2. JUDUL SURAT --}}
    <div class="judul-surat">
        <div class="judul-main">
            SURAT PERINTAH PELAKSANAAN AUDIT (SP2A)
        </div>
    </div>

    {{-- 3. META INFO --}}
    <table class="meta-info">
        <tr>
            <td class="label">Kepada Yth.</td>
            <td class="separator">:</td>
            <td class="content-meta  fw-bold" >
                {{-- LOGIKA HANDLING ARRAY TANPA NOMOR --}}
                @php
                    $kepadaList = is_array($sp2a->kepada_nama) 
                        ? $sp2a->kepada_nama 
                        : ($sp2a->kepada_nama ? preg_split('/\r\n|\r|\n/', $sp2a->kepada_nama) : []);
                @endphp

                @foreach($kepadaList as $nama)
                    {{-- Hapus {{ $loop->iteration }}. atau {{ $index + 1 }}. --}}
                    <div>{{ $nama }}</div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="label">Dari</td>
            <td class="separator">:</td>
            <td class="content-meta">{{ $sp2a->dari_nama }}</td>
        </tr>
        <tr>
            <td class="label">Nomor</td>
            <td class="separator">:</td>
            <td class="content-meta">
                @if($sp2a->current_step == 'finished')
                    {{ $sp2a->nomor_sp2a }}
                @else
                    <i>/SP2A/PW.00/11.00/07-2025</i>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Lampiran</td>
            <td class="separator">:</td>
            <td class="content-meta">{{ $sp2a->lampiran ?? '1 (satu) berkas' }}</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td class="separator">:</td>
            <td class="content-meta fw-bold">{{ $sp2a->perihal }}</td>
        </tr>
    </table>

    {{-- PEMISAH JARAK ANTARA HEADER DAN ISI (Tanpa BR) --}}
    <div class="spacer"></div>

    {{-- 4. ISI SURAT --}}
    <div class="isi-surat">
        {!! $sp2a->isi_surat !!}
    </div>

    {{-- 5. TANDA TANGAN --}}
    {{-- 5. TANDA TANGAN --}}
    <div class="signature-wrapper">
        <p>Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</p>
        <p>Hormat Kami,</p>

        {{-- PERBAIKAN: Margin top untuk jarak dari "Hormat Kami", Margin bottom kecil agar dekat nama --}}
        <div style="margin-top: 30px; margin-bottom: 5px;">
            @if($sp2a->current_step == 'finished')
                {{-- Font size disesuaikan agar tidak terlalu besar --}}
                <div style="font-weight: bold; text-transform: uppercase; color: #000; font-size: 11pt;">
                    APPROVED BY SYSTEM
                </div>
            @else
                <div style="font-style: italic; color: #555; font-size: 11pt;">
                    [Menunggu Approval GM]
                </div>
            @endif
        </div>

        <p class="fw-bold text-underline" style="margin-bottom:0;">
            {{ $sp2a->penanda_tangan_nama }}
        </p>
        <p style="margin-top:2px;">GM Internal Audit</p>
    </div>

    {{-- 6. TEMBUSAN (CC) --}}
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

    {{-- 7. FOOTER NOTE (Kecil di bawah) --}}
    <div class="footer-note">
        Dokumen ini telah ditandatangani secara elektronik.<br>
        ID Dokumen: {{ $sp2a->nomor_sp2a ?? 'DRAFT-'.$sp2a->id }} | 
        Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
    </div>

</div>

</body>
</html>