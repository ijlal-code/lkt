<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LTK - {{ $ltk->nomor_lkt }}</title>

    <style>
        @page {
            margin: 15px 25px;
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 8.5pt;
            line-height: 1.1;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .bg-grey {
            background: #e0e0e0;
            font-weight: bold;
        }

        .title-main {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .title-sub {
            font-size: 8pt;
        }

        .checkbox {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10pt;
        }

        .line-field {
            
            padding-bottom: 2px;
            margin-bottom: 4px;
        }

        .field-problem {
            min-height: 55px;
            
            margin-bottom: 6px;
        }

        .field-akarmasalah {
            min-height: 40px;
            
        }

        .field-tindakan {
            min-height: 55px;
            
        }

        .field-verifikasi {
            min-height: 30px;
            
        }

        .box-paraf,
        .box-auditee {
            border: 1.5px solid #000;
            padding: 6px;
            font-size: 8pt;
        }

        .ref-line {
            
            padding: 3px 0;
            font-size: 8pt;
        }
    </style>
</head>
<body>

<!-- ================= HEADER ================= -->
<table>
    <tr>
        <td style="width:15%; text-align:center;">
            <img src="{{ public_path('img/logo-tonasa.png') }}" width="55">
        </td>
        <td colspan="2" style="text-align:center;">
            <div class="title-main">Laporan Temuan Ketidaksesuaian</div>
            <div><strong>LTK No :</strong> {{ $ltk->nomor_lkt }}</div>
            <div class="title-sub">(Baru / Lanjut)</div>
        </td>
    </tr>

    <tr>
        <td colspan="2" style="width:65%;">
            <table style="border:none;">
                <tr>
                    <td style="border:none; width:30%;">Penerbit</td>
                    <td style="border:none;">
                        : 1. {{ $ltk->penerbit_1 }}<br>
                        : 2. {{ $ltk->penerbit_2 }}<br>
                        : 3. {{ $ltk->penerbit_3 }}
                    </td>
                </tr>
                <tr>
                    <td style="border:none;">Kepada</td>
                    <td style="border:none;">: {{ $ltk->kepada }}</td>
                </tr>
                <tr>
                    <td style="border:none;">Dept / Proses</td>
                    <td style="border:none;">: {{ $ltk->unit_kerja }}</td>
                </tr>
                <tr>
                    <td style="border:none;">Tanggal</td>
                    <td style="border:none;">
                        : {{ \Carbon\Carbon::parse($ltk->tanggal)->format('d F Y') }}
                    </td>
                </tr>
            </table>
        </td>

        <td style="width:35%;">
            <strong>Sumber :</strong><br>
            <span style="font-size:7pt">(Diisi penerbit, dengan √)</span><br><br>

            <span class="checkbox">{{ $ltk->sumber=='Audit Internal'?'☑':'☐' }}</span> Audit Internal<br>
            <span class="checkbox">{{ $ltk->sumber=='SMST'?'☑':'☐' }}</span> SMST<br>
            <span class="checkbox">{{ $ltk->sumber=='Komplain Pelanggan'?'☑':'☐' }}</span> Komplain Pelanggan<br>
            <span class="checkbox">{{ $ltk->sumber=='Proses Perbaikan'?'☑':'☐' }}</span> Proses Perbaikan<br>
            <span class="checkbox">{{ $ltk->sumber=='Tinjauan Manajemen'?'☑':'☐' }}</span> Tinjauan Manajemen<br>
            <span class="checkbox">{{ $ltk->sumber=='Lainnya'?'☑':'☐' }}</span> Lainnya
        </td>
    </tr>
</table>

<!-- ================= TENGAH ================= -->
<table>
    <tr>
        <td colspan="2" class="bg-grey">Gambaran Ketidaksesuaian / Potensi Masalah</td>
    </tr>
    <tr>
        <td style="width:70%;">
            <strong>Problem :</strong>
            <div class="field-problem">{{ $ltk->ketidaksesuaian }}</div>

            <div class="line-field"><strong>Lokasi</strong> : {{ $ltk->lokasi }}</div>
            <div class="line-field"><strong>Bukti Objektif</strong> : {{ $ltk->bukti_objektif }}</div>
        </td>
        <td style="width:30%;">
            <div class="box-paraf">
                <strong>Paraf Auditor :</strong><br><br>
                <strong>Inisial :</strong> {{ $ltk->inisial_auditor }}
            </div>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td class="ref-line">ISO 9001:2015   : klausal, {{ $ltk->iso_9001_klausul }}</td>
    </tr>
    <tr>
        <td class="ref-line">ISO 14001:2015  : klausal, {{ $ltk->iso_14001_klausul }}</td>
    </tr>
    <tr>
        <td class="ref-line">SMK3            : elemen, {{ $ltk->smk3_elemen }}</td>
    </tr>
    <tr>
        <td class="ref-line">ISO 45001:2018  : klausal, {{ $ltk->iso_45001_klausul }}</td>
    </tr>
    <tr>
        <td class="ref-line">LAB 17025:2017  : klausal, {{ $ltk->lab_17025_klausul }}</td>
    </tr>
    <tr>
        <td class="ref-line">ISO 50001:2018 : klausal, {{ $ltk->iso_50001_klausul }}</td>
    </tr>
    <tr>
        <td class="ref-line">SMKP Minerba    : elemen, {{ $ltk->smkp_minerba_elemen }}</td>
    </tr>
    <tr>
        <td class="ref-line">ISO 37001:2016  : elemen, {{ $ltk->iso_37001_elemen }}</td>
    </tr>
</table>

<!-- ================= AKHIR ================= -->
<table>
    <tr>
        <td class="bg-grey">
            Akar Masalah
            <span style="font-size:7pt; font-style:italic;">
                (Identifikasi penyebab utama ketidaksesuaian)
            </span>
        </td>
    </tr>
    <tr>
        <td class="field-akarmasalah">{{ $ltk->akar_penyebab }}</td>
    </tr>
</table>

<table>
    <tr>
        <td colspan="2" class="bg-grey">
            Rencana Tindakan Perbaikan
            <span style="font-size:7pt; font-style:italic;">
                (Tindakan perbaikan / koreksi)
            </span>
        </td>
    </tr>
    <tr>
        <td style="width:70%;" class="field-tindakan">{{ $ltk->tindakan_perbaikan }}</td>
        <td style="width:30%;">
            <div class="box-auditee">
                <strong>Auditee :</strong><br>
                <strong>Paraf / Inisial :</strong><br>
                <strong>Tgl. Selesai :</strong>
                {{ \Carbon\Carbon::parse($ltk->target_penyelesaian)->format('d-m-Y') }}
            </div>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td colspan="2" class="bg-grey">Verifikasi</td>
    </tr>
    <tr>
        <td style="width:65%;">
            <div class="line-field">
                <strong>Tanggal :</strong>
                {{ $ltk->tanggal_verifikasi
                    ? \Carbon\Carbon::parse($ltk->tanggal_verifikasi)->format('d-m-Y')
                    : '' }}
            </div>
            <div class="field-verifikasi">
                <strong>Komentar :</strong> {{ $ltk->verifikasi_tindakan }}
            </div>
        </td>
        <td style="width:35%;">
            <strong>Status :</strong><br>
            <span class="checkbox">{{ $ltk->status=='Selesai'?'☑':'☐' }}</span> Selesai<br>
            <span class="checkbox">{{ $ltk->status=='Lanjut'?'☑':'☐' }}</span> Lanjut<br><br>

            <strong>Kategori :</strong><br>
            <span class="checkbox">{{ $ltk->kategori_temuan=='Fatality'?'☑':'☐' }}</span> Fatality<br>
            <span class="checkbox">{{ $ltk->kategori_temuan=='Major'?'☑':'☐' }}</span> Major<br>
            <span class="checkbox">{{ $ltk->kategori_temuan=='Minor'?'☑':'☐' }}</span> Minor<br>
            <span class="checkbox">{{ $ltk->kategori_temuan=='Observasi'?'☑':'☐' }}</span> Observasi
        </td>
    </tr>
</table>

<table>
    <tr>
        <td style="width:65%;"></td>
        <td style="width:35%;">
            <div class="box-paraf">
                <strong>Penerbit :</strong><br>
                <strong>Paraf :</strong><br>
                <strong>Inisial :</strong> {{ $ltk->inisial_auditor }}
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="line-field">
            <strong>Dilanjutkan ke LTK No :</strong>
            {{ $ltk->dilanjutkan_ke_ltk_no }}
        </td>
    </tr>
</table>

</body>
</html>
