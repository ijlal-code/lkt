<!DOCTYPE html>
<html>
<head>
    <title>Surat Peringatan 2A</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .header { background: #dc3545; color: white; padding: 15px; text-align: center; }
        .content { padding: 20px; }
        .footer { font-size: 0.8rem; color: #777; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        td:first-child { font-weight: bold; width: 150px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>PEMBERITAHUAN SP2A</h2>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $sp2a->kepada_nama }}</strong>,</p>

        <p>Bersama ini kami sampaikan bahwa <strong>Surat Peringatan 2A (SP2A)</strong> telah diterbitkan dengan rincian sebagai berikut:</p>

        <table>
            <tr>
                <td>Nomor Surat</td>
                <td>: {{ $sp2a->nomor_sp2a }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($sp2a->tanggal_surat)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>: {{ $sp2a->perihal }}</td>
            </tr>
        </table>

        <p><strong>Dasar Penerbitan:</strong><br>
        {{ $sp2a->dasar_surat }}</p>

        <p><strong>Isi Peringatan:</strong><br>
        {{ $sp2a->isi_surat }}</p>

        <p>Mohon agar surat ini dapat ditindaklanjuti sebagaimana mestinya.</p>

        <br>
        <p>Hormat Kami,<br>
        <strong>Tim Audit Internal</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Audit. Mohon tidak membalas email ini.</p>
    </div>

</body>
</html>