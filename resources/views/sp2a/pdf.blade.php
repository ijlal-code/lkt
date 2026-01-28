<!DOCTYPE html>
<html>
<head>
    <title>Surat Peringatan 2A</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11pt; 
            line-height: 1.4; 
            color: #000;
            margin: 0; 
            padding: 0;
        }
        
        /* HEADER SURAT */
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 3px double #000; 
            padding-bottom: 10px; 
        }
        .header h3 { 
            margin: 0; 
            text-decoration: underline; 
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header p { 
            margin: 5px 0 0; 
            font-size: 11pt;
        }

        /* TABEL INFORMASI (Kepada, Dari, Perihal) */
        .info-table { 
            width: 100%; 
            margin-bottom: 25px; 
        }
        .info-table td { 
            vertical-align: top; 
            padding: 3px 0; 
        }
        .label-col { width: 100px; }
        .sep-col { width: 15px; text-align: center; }

        /* ISI SURAT (Hasil dari Editor) */
        .content { 
            text-align: justify; 
            margin-bottom: 40px;
        }
        /* Styling khusus agar list/tabel dari Word/CKEditor rapi di PDF */
        .content ul, .content ol { margin-left: 20px; padding-left: 15px; }
        .content li { margin-bottom: 5px; }
        .content p { margin-top: 0; margin-bottom: 10px; }
        .content table { 
            width: 100%; border-collapse: collapse; margin: 10px 0; 
        }
        .content table, .content th, .content td { 
            border: 1px solid black; padding: 5px; 
        }

        /* AREA TANDA TANGAN */
        .ttd-container {
            width: 100%;
            margin-top: 50px;
            position: relative;
            height: 160px; /* Tinggi area tanda tangan */
        }
        .ttd-box {
            position: absolute;
            right: 0;
            width: 250px;
            text-align: center;
        }

        /* CAP DIGITAL (STAMP) */
        .digital-stamp {
            border: 3px solid #28a745; 
            color: #28a745; 
            padding: 10px; 
            display: inline-block;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            text-transform: uppercase;
            border-radius: 5px;
            margin: 15px 0;
            /* Efek Miring agar terlihat seperti cap basah */
            transform: rotate(-8deg); 
            opacity: 0.9;
        }
        .timestamp {
            display: block;
            font-size: 8pt;
            margin-top: 5px;
            border-top: 1px solid #28a745;
            padding-top: 2px;
            color: #28a745;
        }

        /* TEMBUSAN / CC */
        .cc {
            clear: both;
            margin-top: 40px;
            font-size: 9pt;
            color: #444;
        }
        .cc b { text-decoration: underline; color: #000; }
        .cc ul { margin-top: 5px; padding-left: 20px; list-style-type: circle; }
        .cc li { margin-bottom: 2px; }
    </style>
</head>
<body>

    <div class="header">
        <h3>SURAT PERINGATAN 2A (SP2A)</h3>
        <p>Nomor: {{ $sp2a->nomor_sp2a ?? 'DRAFT / BELUM DIPROSES' }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label-col">Kepada Yth.</td>
            <td class="sep-col">:</td>
            <td><strong>{{ $sp2a->kepada_nama }}</strong></td>
        </tr>
        <tr>
            <td class="label-col">Dari</td>
            <td class="sep-col">:</td>
            <td>{{ $sp2a->dari_nama }}</td>
        </tr>
        <tr>
            <td class="label-col">Tanggal</td>
            <td class="sep-col">:</td>
            <td>{{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label-col">Perihal</td>
            <td class="sep-col">:</td>
            <td>{{ $sp2a->perihal }}</td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 1px solid #ccc; margin-bottom: 20px;">

    <div class="content">
        {!! $sp2a->isi_surat !!}
    </div>

    <div class="ttd-container" style="margin-top: 30px; float: right; width: 250px; text-align: center;">
    <div class="ttd-box">
        <p style="margin-bottom: 0;">Hormat Kami,</p>
        
        <div style="height: 60px;"></div>

        <div style="display: flex; flex-direction: column; align-items: center;">
            @if($sp2a->status == 'Approved By System')
                <div style="font-family: 'Courier', monospace; font-size: 11px; font-weight: bold; color: #000; margin-bottom: -3px;">
                    APPROVED BY SYSTEM
                </div>
            @endif

            <p style="margin-top: 0; margin-bottom: 0;">
                <strong><u>{{ $sp2a->penanda_tangan_nama }}</u></strong>
            </p>
        </div>
    </div>
</div>
<div style="clear: both;"></div>

    @if($sp2a->email_auditor || $sp2a->email_k3 || $sp2a->email_staff || $sp2a->email_atasan)
    <div class="cc">
        <b>Tembusan Disampaikan Kepada Yth:</b>
        <ul>
            @if($sp2a->email_auditor) <li>Auditor ({{ $sp2a->email_auditor }})</li> @endif
            @if($sp2a->email_k3)      <li>Staff K3 ({{ $sp2a->email_k3 }})</li> @endif
            @if($sp2a->email_staff)   <li>Staff Unit ({{ $sp2a->email_staff }})</li> @endif
            @if($sp2a->email_atasan)  <li>Atasan Staff ({{ $sp2a->email_atasan }})</li> @endif
        </ul>
    </div>
    @endif

</body>
</html>