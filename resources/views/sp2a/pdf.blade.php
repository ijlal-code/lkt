<!DOCTYPE html>
<html>
<head>
    <title>Surat Peringatan 2A</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11pt; 
            line-height: 1.5; 
            color: #000;
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

        /* TABEL INFO (Kepada, Dari, dll) */
        .info-table { 
            width: 100%; 
            margin-bottom: 25px; 
        }
        .info-table td { 
            vertical-align: top; 
            padding: 2px 0; 
        }
        .label-col { width: 100px; }
        .sep-col { width: 15px; text-align: center; }

        /* ISI SURAT (Hasil CKEditor) */
        .content { 
            text-align: justify; 
            margin-bottom: 40px;
        }
        /* Styling khusus agar list/tabel dari Word rapi di PDF */
        .content ul, .content ol { margin-left: 20px; padding-left: 15px; }
        .content li { margin-bottom: 5px; }
        .content p { margin-top: 0; margin-bottom: 10px; }
        .content table { 
            width: 100%; border-collapse: collapse; margin: 10px 0; 
        }
        .content table, .content th, .content td { 
            border: 1px solid black; padding: 5px; 
        }

        /* TANDA TANGAN */
        .ttd-container {
            width: 100%;
            margin-top: 50px;
            /* Menggunakan tabel layout untuk posisi tanda tangan agar stabil di PDF */
        }
        .ttd-box {
            float: right;
            width: 250px;
            text-align: center;
        }

        /* TEMBUSAN / CC */
        .cc {
            clear: both;
            margin-top: 80px;
            font-size: 10pt;
        }
        .cc b { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="header">
        <h3>SURAT PERINGATAN 2A (SP2A)</h3>
        <p>Nomor: {{ $sp2a->nomor_sp2a }}</p>
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

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Hormat Kami,</p>
            <br><br><br><br>
            <p><strong><u>{{ $sp2a->penanda_tangan_nama }}</u></strong></p>
        </div>
    </div>

    @if($sp2a->cc_nama)
    <div class="cc">
        <b>Tembusan:</b><br>
        - {{ $sp2a->cc_nama }}
    </div>
    @endif

</body>
</html>