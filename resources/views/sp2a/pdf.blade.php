<!DOCTYPE html>
<html>
<head>
    <title>Surat Peringatan 2A</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin-top: 20px; text-align: justify; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table td { padding: 5px; vertical-align: top; }
        .ttd { margin-top: 50px; text-align: right; margin-right: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h3>SURAT PERINGATAN 2A (SP2A)</h3>
        <p>Nomor: {{ $sp2a->nomor_sp2a }}</p>
    </div>

    <div class="content">
        <p>Kepada Yth,<br>
        <strong>{{ $sp2a->kepada_nama }}</strong><br>
        di Tempat</p>

        <p>Dengan hormat,</p>
        <p>Berdasarkan hasil evaluasi dan audit internal, ditemukan ketidaksesuaian dengan rincian sebagai berikut:</p>

        <table class="table">
            <tr>
                <td width="150"><strong>Dasar Penerbitan</strong></td>
                <td>: {{ $sp2a->dasar_surat }}</td>
            </tr>
            <tr>
                <td><strong>Isi Peringatan</strong></td>
                <td>: {{ $sp2a->isi_surat }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Surat</strong></td>
                <td>: {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <p>Demikian surat peringatan ini disampaikan untuk menjadi perhatian dan segera ditindaklanjuti.</p>
    </div>

    <div class="ttd">
        <p>Hormat Kami,</p>
        <br><br>
        <p><strong>Tim Audit Internal</strong></p>
    </div>
</body>
</html>