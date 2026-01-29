<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SP2A - {{ $sp2a->nomor_sp2a ?? 'Draft' }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .header-logo {
            width: 80px;
        }
        .header-text {
            text-align: center;
        }
        .header-text h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .content {
            margin: 20px 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .isi-surat {
            text-align: justify;
            min-height: 300px;
        }
        .footer-table {
            width: 100%;
            margin-top: 50px;
        }
        .ttd-container {
            width: 250px;
            text-align: center;
        }
        /* Style khusus untuk tanda tangan otomatis */
        .approved-text {
            font-family: 'Courier', monospace;
            font-size: 11pt;
            font-weight: bold;
            color: #000;
            margin-bottom: -3px; /* Merapatkan ke garis nama */
            text-transform: uppercase;
        }
        .timestamp {
            font-size: 8pt;
            font-weight: normal;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('img/logo-tonasa.png') }}" width="70">
            </td>
            <td class="header-text">
                <h2>Internal Audit Department</h2>
                <p style="margin: 0; font-size: 10pt;">PT Semen Tonasa - Pangkep, Sulawesi Selatan</p>
            </td>
            <td class="header-logo" style="text-align: right;">
                <img src="{{ public_path('img/logo-internal-audit.png') }}" width="70">
            </td>
        </tr>
    </table>

    <div class="content">
        <center>
            <h3 style="text-decoration: underline; margin-bottom: 5px;">SURAT PERINGATAN 2A (SP2A)</h3>
            <p style="margin-top: 0;">
                Nomor: {{ $sp2a->status == 'Approved By System' ? $sp2a->nomor_sp2a : '..........................................' }}
            </p>
        </center>

        <table class="info-table">
            <tr>
                <td width="15%">Tanggal</td>
                <td width="2%">:</td>
                <td>{{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Kepada</td>
                <td>:</td>
                <td><strong>{{ $sp2a->kepada_nama }}</strong></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td>{{ $sp2a->perihal }}</td>
            </tr>
        </table>

        <div class="isi-surat">
            {!! $sp2a->isi_surat !!}
        </div>

        <table width="100%" style="margin-top: 40px;">
            <tr>
                <td width="60%"></td>
                <td width="40%" align="center">
                    <p style="margin-bottom: 0;">Hormat Kami,</p>
                    
                    <div style="height: 50px; position: relative;">
                        @if($sp2a->status == 'Approved By System')
                            <div style="margin-top: 30px;">
                                <div class="approved-text">APPROVED BY SYSTEM</div>
                            </div>
                        @else
                            <div style="height: 50px;"></div>
                        @endif
                    </div>

                    <p style="margin-top: 0; margin-bottom: 0;">
                        <strong><u>{{ $sp2a->penanda_tangan_nama }}</u></strong>
                    </p>
                    <p style="margin-top: 0; font-size: 10pt;">Kepala Departemen Audit</p>
                </td>
            </tr>
        </table>
    </div>

    <div style="position: fixed; bottom: 0; width: 100%; font-size: 8pt; color: #666; border-top: 1px solid #ddd; padding-top: 5px;">
        Dokumen ini diterbitkan secara sistem melalui Sistem Audit Internal PT Semen Tonasa.
        @if($sp2a->status == 'Approved By System')
             Diverifikasi pada: {{ \Carbon\Carbon::parse($sp2a->approved_at)->format('d/m/Y H:i') }}
        @endif
    </div>

</body>
</html>