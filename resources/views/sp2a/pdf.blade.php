<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'DRAFT' }}</title>

    <style>
        @page { margin: 1cm 2cm; }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }

        .container { width: 100%; }

        /* ================= KOP SURAT ================= */
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

        /* ================= JUDUL ================= */
        .judul-surat {
            text-align: center;
            margin-bottom: 25px;
        }
        .judul-main {
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin: 0;
        }

        /* ================= META INFO ================= */
        table.meta-info {
            width: 100%;
            margin-bottom: 20px;
        }
        table.meta-info td {
            vertical-align: top;
            padding: 2px 0;
        }
        .label { width: 110px; }
        .separator { width: 15px; text-align: center; }

        /* ================= ISI ================= */
        .isi-surat {
            text-align: justify;
            margin-bottom: 40px;
            min-height: 150px;
        }

        /* ================= TTD ================= */
        .signature-wrapper {
            margin-top: 30px;
            width: 50%;
        }

        .status-box {
            margin: 15px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* ================= CC & FOOTER ================= */
        .cc-section {
            margin-top: 40px;
            font-size: 11pt;
        }
        .cc-label {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer-note {
            margin-top: 20px;
            font-size: 9pt;
            color: #555;
            font-style: italic;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

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
                <img src="{{ public_path('img/logo-sig.png') }}" width="70">
            </td>
            <td>
                <h2 class="kop-title">PT Semen Tonasa</h2>
                <p class="kop-subtitle">Unit Internal Audit</p>
            </td>
            <td width="15%" style="text-align:right;">
                <img src="{{ public_path('img/logo-tonasa.png') }}" width="70">
            </td>
        </tr>
    </table>

    {{-- 2. JUDUL SURAT (SESUAI SHOW & FOTO) --}}
    <div class="judul-surat">
        <div class="judul-main">
            SURAT PERINTAH PELAKSANAAN AUDIT (SP2A)
        </div>
    </div>

    {{-- 3. META INFO (SESUAI FOTO & SHOW) --}}
    <table class="meta-info">
        <tr>
            <td class="label">Kepada Yth.</td>
            <td class="separator">:</td>
            <td class="fw-bold">
                {!! nl2br(e($sp2a->kepada_nama)) !!}
            </td>
        </tr>
        <tr>
            <td class="label">Dari</td>
            <td class="separator">:</td>
            <td>{{ $sp2a->dari_nama }}</td>
        </tr>
        <tr>
            <td class="label">Nomor</td>
            <td class="separator">:</td>
            <td>
                @if($sp2a->current_step == 'finished')
                    {{ $sp2a->nomor_sp2a }}
                @else
                    <span class="text-danger">[Nomor Belum Terbit]</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Lampiran</td>
            <td class="separator">:</td>
            <td>{{ $sp2a->lampiran ?? '1 (satu) berkas' }}</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td class="separator">:</td>
            <td class="fw-bold">{{ $sp2a->perihal }}</td>
        </tr>
    </table>

    {{-- 4. ISI SURAT --}}
    <div class="isi-surat">
        {!! $sp2a->isi_surat !!}
    </div>

    {{-- 5. TANDA TANGAN --}}
    <div class="signature-wrapper">
        <p>Pangkep, {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</p>
        <p>Hormat Kami,</p>

        <div class="status-box">
            @if($sp2a->current_step == 'finished')
                APPROVED BY SYSTEM
            @else
                <span style="font-weight:normal; font-style:italic; color:#888;">
                    [Menunggu Approval GM]
                </span>
            @endif
        </div>

        <p class="fw-bold text-underline" style="margin-bottom:0;">
            {{ $sp2a->penanda_tangan_nama }}
        </p>
        <p style="margin-top:2px;">GM Internal Audit</p>
    </div>

    {{-- 6. CC --}}
    @if(!empty($sp2a->tembusan) && count($sp2a->tembusan) > 0)
    <div class="cc-section">
        <div class="cc-label">Cc:</div>
        <ol>
            @foreach($sp2a->tembusan as $cc)
                <li>{{ $cc }}</li>
            @endforeach
        </ol>
    </div>
    @endif

    {{-- 7. FOOTER --}}
    <div class="footer-note">
        Dokumen ini telah ditandatangani secara elektronik.<br>
        ID Dokumen: {{ $sp2a->nomor_sp2a ?? 'DRAFT-'.$sp2a->id }} |
        Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
    </div>

</div>

</body>
</html>
