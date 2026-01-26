<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>LKT - {{ $ltk->nomor_lkt }}</title>
    <style>
        /* SETUP KERTAS A4 COMPACT (1 HALAMAN) */
        @page {
            margin: 15px 25px; /* Atas/Bawah 15px, Kiri/Kanan 25px */
        }

        body {
            font-family: "DejaVu Sans", sans-serif; /* Wajib untuk simbol Checklist */
            font-size: 8.5pt; /* Ukuran font pas untuk 1 halaman padat */
            color: #000;
            line-height: 1.1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: -1px; /* Trik agar garis tabel menyatu (tidak double) */
        }

        td, th {
            border: 1px solid #000;
            padding: 3px 4px; /* Padding minimalis */
            vertical-align: top;
            word-wrap: break-word;
        }

        /* Styling Khusus Header (Tanpa Garis Vertikal Tengah) */
        .header-table td { border-bottom: 1px solid #000; border-top: 1px solid #000; }
        .no-border-right { border-right: none !important; }
        .no-border-left { border-left: none !important; }
        .no-border-top { border-top: none !important; }
        .no-border-bottom { border-bottom: none !important; }

        /* Utility */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .bg-grey { background-color: #e0e0e0; font-weight: bold; padding: 2px 4px; }
        .checkbox { font-family: "DejaVu Sans", sans-serif; font-size: 10pt; }
        
        /* Tinggi Baris Input (Agar konsisten & muat 1 hal) */
        .field-sm { height: 20px; }
        .field-md { height: 50px; } /* Uraian Temuan */
        .field-lg { height: 40px; } /* Akar & Tindakan */

        /* Judul Besar */
        .title-main { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
        .title-sub { font-size: 8pt; }
    </style>
</head>
<body>

    <table class="header-table" style="margin-bottom: 0;">
        <tr>
            <td class="no-border-right" style="width: 15%; text-align: center; vertical-align: middle; height: 60px;">
                <img src="{{ public_path('img/logo-tonasa.png') }}" width="55" alt="Logo">
            </td>
            <td class="no-border-left no-border-right" style="width: 65%; text-align: center; vertical-align: middle;">
                <div class="title-main">Laporan Temuan Ketidaksesuaian</div>
                <div class="title-main">(LTK)</div>
                <div style="margin-top: 4px;"><strong>No. LTK:</strong> {{ $ltk->nomor_lkt }}</div>
                <div class="title-sub">(Baru / Revisi)</div>
            </td>
            <td class="no-border-left" style="width: 20%; vertical-align: middle; font-size: 7pt;">
                <strong>No. Dok:</strong> FP/ST/SYM/001<br>
                <strong>Revisi:</strong> 00<br>
                <strong>Halaman:</strong> 1 dari 1
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 55%;">
                <span class="text-bold">Penerbit:</span><br>
                1. {{ $ltk->penerbit_1 ?? '..............................' }}<br>
                2. {{ $ltk->penerbit_2 ?? '..............................' }}<br>
                3. {{ $ltk->penerbit_3 ?? '..............................' }}
            </td>
            <td style="width: 45%;" rowspan="2">
                <span class="text-bold">Sumber:</span>
                <table style="border: none; margin: 0;">
                    <tr style="border: none;">
                        <td style="border: none; width: 50%; padding: 0;">
                            <span class="checkbox">{{ $ltk->sumber == 'Audit Internal' ? '☑' : '☐' }}</span> Audit Internal<br>
                            <span class="checkbox">{{ $ltk->sumber == 'SMST' ? '☑' : '☐' }}</span> SMST<br>
                            <span class="checkbox">{{ $ltk->sumber == 'Komplain Pelanggan' ? '☑' : '☐' }}</span> Komplain Plg
                        </td>
                        <td style="border: none; width: 50%; padding: 0;">
                            <span class="checkbox">{{ $ltk->sumber == 'Proses Perbaikan' ? '☑' : '☐' }}</span> Proses Perbaikan<br>
                            <span class="checkbox">{{ $ltk->sumber == 'Tinjauan Manajemen' ? '☑' : '☐' }}</span> Tinjauan Mnj<br>
                            <span class="checkbox">{{ $ltk->sumber == 'Lainnya' ? '☑' : '☐' }}</span> Lainnya: {{ $ltk->sumber_lainnya_text ?? '...' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <span class="text-bold">Kepada:</span> {{ $ltk->kepada }} <br>
                <span class="text-bold">Dept/Proses:</span> {{ $ltk->unit_kerja }} <br>
                <span class="text-bold">Tanggal Laporan:</span> {{ \Carbon\Carbon::parse($ltk->tanggal)->format('d-m-Y') }}
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="bg-grey">Gambaran Ketidaksesuaian / Potensi Masalah</td></tr>
        <tr>
            <td colspan="2" class="field-md">
                {{ $ltk->ketidaksesuaian }}
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <span class="text-bold">Lokasi:</span> {{ $ltk->lokasi ?? '-' }}
            </td>
            <td style="width: 50%;">
                <span class="text-bold">Bukti Objektif:</span> {{ $ltk->bukti_objektif ?? '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 2px;">
                <table style="border: none; margin: 0;">
                    <tr style="border: none;">
                        <td style="border: none; width: 70%;">
                            <span class="text-bold">Paraf Auditor:</span> 
                            </td>
                        <td style="border: none; width: 30%;">
                            <span class="text-bold">Inisial:</span> {{ $ltk->inisial_auditor ?? '...' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="bg-grey">Referensi (Standar / Klausul)</td></tr>
        <tr>
            <td style="width: 50%; font-size: 8pt; border-right: none;">
                ISO 9001:2015: {{ $ltk->iso_9001_klausul ?? '...' }}<br>
                ISO 14001:2015: {{ $ltk->iso_14001_klausul ?? '...' }}<br>
                SMK3 Elemen: {{ $ltk->smk3_elemen ?? '...' }}<br>
                ISO 45001:2018: {{ $ltk->iso_45001_klausul ?? '...' }}
            </td>
            <td style="width: 50%; font-size: 8pt; border-left: none;">
                LAB 17025:2017: {{ $ltk->lab_17025_klausul ?? '...' }}<br>
                ISO 50001:2018: {{ $ltk->iso_50001_klausul ?? '...' }}<br>
                SMKP Minerba: {{ $ltk->smkp_minerba_elemen ?? '...' }}<br>
                ISO 37001:2016: {{ $ltk->iso_37001_elemen ?? '...' }}
            </td>
        </tr>
    </table>

    <table>
        <tr><td class="bg-grey">Akar Masalah (Root Cause Analysis)</td></tr>
        <tr>
            <td class="field-lg">
                {{ $ltk->akar_penyebab }}
            </td>
        </tr>
    </table>

    <table>
        <tr><td class="bg-grey">Rencana Tindakan Perbaikan</td></tr>
        <tr>
            <td class="field-lg">
                {{ $ltk->tindakan_perbaikan }}
            </td>
        </tr>
        <tr>
            <td>
                <table style="border: none; margin: 0;">
                    <tr style="border: none;">
                        <td style="border: none; width: 65%;">
                            <span class="text-bold">Auditee / Penanggung Jawab:</span>
                            {{ $ltk->auditee_nama ?? '....................' }} (Paraf: ...........)
                        </td>
                        <td style="border: none; width: 35%; text-align: right;">
                            <span class="text-bold">Target Selesai:</span> 
                            {{ \Carbon\Carbon::parse($ltk->target_penyelesaian)->format('d-m-Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="bg-grey">Verifikasi Tindakan</td></tr>
        <tr>
            <td colspan="2">
                <span class="text-bold">Komentar Verifikasi:</span><br>
                <div class="field-sm">{{ $ltk->verifikasi_tindakan }}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 45%;">
                <span class="text-bold">Status Temuan:</span><br>
                <span class="checkbox">{{ $ltk->status == 'Selesai' ? '☑' : '☐' }}</span> Selesai (Closed) &nbsp;
                <span class="checkbox">{{ $ltk->status == 'Lanjut' ? '☑' : '☐' }}</span> Lanjut (Open)
            </td>
            <td style="width: 55%;">
                <span class="text-bold">Kategori Temuan:</span><br>
                <span class="checkbox">{{ $ltk->kategori_temuan == 'Fatality' ? '☑' : '☐' }}</span> Fatality
                <span class="checkbox">{{ $ltk->kategori_temuan == 'Major' ? '☑' : '☐' }}</span> Major
                <span class="checkbox">{{ $ltk->kategori_temuan == 'Minor' ? '☑' : '☐' }}</span> Minor
                <span class="checkbox">{{ $ltk->kategori_temuan == 'Observasi' ? '☑' : '☐' }}</span> Obs
            </td>
        </tr>
        <tr>
            <td>
                <span class="text-bold">Tanggal Verifikasi:</span><br>
                {{ $ltk->tanggal_verifikasi ? \Carbon\Carbon::parse($ltk->tanggal_verifikasi)->format('d-m-Y') : '....................' }}
            </td>
            <td>
                <span class="text-bold">Penerbit / Penutup (Auditor):</span><br>
                <br>
                ( ................................. )
            </td>
        </tr>
    </table>

    @if($ltk->dilanjutkan_ke_ltk_no)
    <div style="font-size: 7pt; margin-top: 2px;">
        <em>* Dilanjutkan ke LTK No: {{ $ltk->dilanjutkan_ke_ltk_no }}</em>
    </div>
    @endif

</body>
</html>