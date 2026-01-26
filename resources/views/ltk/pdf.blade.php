<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>LTK - {{ $ltk->nomor_lkt }}</title>
    <style>
        /* SETUP HALAMAN AGAR 1 LEMBAR */
        @page {
            margin: 20px 30px; /* Margin atas/bawah 20px, kiri/kanan 30px */
        }

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 9pt; /* Font diperkecil sedikit agar muat */
            line-height: 1.1; /* Jarak antar baris diperpadat */
            color: #000;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 5px; /* Jarak antar tabel diperkecil */
        }
        
        td, th {
            border: 1px solid #000;
            padding: 3px; /* Padding diperkecil */
            vertical-align: top;
            word-wrap: break-word;
        }
        
        /* Utility Classes */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-right { text-align: right; }
        .bg-grey { background-color: #f0f0f0; }
        .no-border { border: none !important; }
        
        /* PENGATURAN TINGGI KOLOM AGAR MUAT 1 HALAMAN */
        .h-field-sm { height: 25px; }
        .h-field-md { height: 50px; } /* Dikurangi dari 80px */
        .h-field-lg { height: 90px; }
        
        .checkbox {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10pt;
        }

        .small-text { font-size: 7pt; }
        
        .header-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Hapus border spesifik untuk Header */
        .no-border-right { border-right: none !important; }
        .no-border-left { border-left: none !important; }
    </style>
</head>
<body>

    <table>
        <tr>
            <td class="no-border-right" style="width: 15%; text-align: center; vertical-align: middle;">
                <img src="{{ public_path('img/logo-tonasa.png') }}" width="50" alt="Logo">
            </td>
            <td class="no-border-left no-border-right" style="width: 60%; text-align: center; vertical-align: middle;">
                <div class="header-title">Laporan Temuan Ketidaksesuaian</div>
                <div class="header-title">(LTK)</div>
                <div style="margin-top: 5px;"><strong>No. LTK:</strong> {{ $ltk->nomor_lkt }}</div>
                <div class="small-text">(Baru / Revisi)</div>
            </td>
            <td class="no-border-left" style="width: 25%; vertical-align: middle; font-size: 7pt;">
                <strong>No. Dok:</strong> FP/ST/SYM/001<br>
                <strong>Revisi:</strong> 00<br>
                <strong>Halaman:</strong> 1 dari 1
            </td>
        </tr>
    </table>

    <table style="border-bottom: none; margin-top: -6px;">
        <tr>
            <td style="width: 60%;">
                <span class="text-bold">Penerbit:</span><br>
                1. {{ $ltk->penerbit_1 ?? '..............................' }}<br>
                2. {{ $ltk->penerbit_2 ?? '..............................' }}<br>
                3. {{ $ltk->penerbit_3 ?? '..............................' }}
            </td>
            <td style="width: 40%;" rowspan="2">
                <span class="text-bold">Sumber:</span><br>
                <span class="checkbox">{{ ($ltk->sumber == 'Audit Internal') ? '☑' : '☐' }}</span> Audit Internal (SKAI)<br>
                <span class="checkbox">{{ ($ltk->sumber == 'SMST') ? '☑' : '☐' }}</span> SMST 
                <span class="checkbox">{{ ($ltk->sumber == 'Komplain Pelanggan') ? '☑' : '☐' }}</span> Komplain<br>
                <span class="checkbox">{{ ($ltk->sumber == 'Proses Perbaikan') ? '☑' : '☐' }}</span> Perbaikan
                <span class="checkbox">{{ ($ltk->sumber == 'Tinjauan Manajemen') ? '☑' : '☐' }}</span> Tinjauan Mnj<br>
                <span class="checkbox">{{ ($ltk->sumber == 'Lainnya') ? '☑' : '☐' }}</span> Lainnya: {{ $ltk->sumber_lainnya_text ?? '...' }}
            </td>
        </tr>
        <tr>
            <td>
                <span class="text-bold">Kepada:</span> {{ $ltk->kepada }} <br>
                <span class="text-bold">Dept / Proses:</span> {{ $ltk->unit_kerja }} <br>
                <span class="text-bold">Tanggal Laporan:</span> {{ \Carbon\Carbon::parse($ltk->tanggal)->format('d-m-Y') }}
            </td>
        </tr>
    </table>

    <table style="margin-top: -1px;">
        <tr>
            <td colspan="2" class="bg-grey text-bold" style="padding: 2px;">Gambaran Ketidaksesuaian / Potensi Masalah</td>
        </tr>
        <tr>
            <td colspan="2" class="h-field-md">
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
            <td colspan="2" style="padding: 1px;">
                <table style="width: 100%; border: none; margin: 0;">
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

    <table style="margin-top: -1px;">
        <tr>
            <td colspan="2" class="bg-grey text-bold" style="padding: 2px;">Referensi (Standar / Klausul)</td>
        </tr>
        <tr>
            <td style="width: 50%; border-right: none; font-size: 8pt;">
                ISO 9001:2015 Klausul: {{ $ltk->iso_9001_klausul ?? '...' }}<br>
                ISO 14001:2015 Klausul: {{ $ltk->iso_14001_klausul ?? '...' }}<br>
                SMK3 Elemen: {{ $ltk->smk3_elemen ?? '...' }}<br>
                ISO 45001:2018 Klausul: {{ $ltk->iso_45001_klausul ?? '...' }}
            </td>
            <td style="width: 50%; border-left: none; font-size: 8pt;">
                LAB 17025:2017 Klausul: {{ $ltk->lab_17025_klausul ?? '...' }}<br>
                ISO 50001:2018 Klausul: {{ $ltk->iso_50001_klausul ?? '...' }}<br>
                SMKP Minerba Elemen: {{ $ltk->smkp_minerba_elemen ?? '...' }}<br>
                ISO 37001:2016 Elemen: {{ $ltk->iso_37001_elemen ?? '...' }}
            </td>
        </tr>
    </table>

    <table style="margin-top: -1px;">
        <tr>
            <td class="bg-grey text-bold" style="padding: 2px;">Akar Masalah (Root Cause Analysis)</td>
        </tr>
        <tr>
            <td class="h-field-md">
                {{ $ltk->akar_penyebab }}
            </td>
        </tr>
    </table>

    <table style="margin-top: -1px;">
        <tr>
            <td class="bg-grey text-bold" style="padding: 2px;">Rencana Tindakan Perbaikan</td>
        </tr>
        <tr>
            <td class="h-field-md">
                {{ $ltk->tindakan_perbaikan }}
            </td>
        </tr>
        <tr>
            <td style="padding: 1px;">
                <table style="width: 100%; border: none; margin: 0;">
                    <tr style="border: none;">
                        <td style="border: none; width: 60%;">
                            <span class="text-bold">Auditee / Penanggung Jawab:</span>
                            {{ $ltk->auditee_nama ?? '....................' }} (Paraf: ...........)
                        </td>
                        <td style="border: none; width: 40%; text-align: right;">
                            <span class="text-bold">Target Selesai:</span> {{ \Carbon\Carbon::parse($ltk->target_penyelesaian)->format('d-m-Y') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="margin-top: -1px;">
        <tr>
            <td colspan="2" class="bg-grey text-bold" style="padding: 2px;">Verifikasi Tindakan</td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="text-bold">Komentar Verifikasi:</span><br>
                <div class="h-field-sm">{{ $ltk->verifikasi_tindakan }}</div>
            </td>
        </tr>
        <tr>
            <td style="width: 45%;">
                <span class="text-bold">Status Temuan:</span><br>
                <span class="checkbox">{{ ($ltk->status == 'Selesai') ? '☑' : '☐' }}</span> Selesai (Closed) &nbsp;
                <span class="checkbox">{{ ($ltk->status == 'Lanjut') ? '☑' : '☐' }}</span> Lanjut (Open)
            </td>
            <td style="width: 55%;">
                <span class="text-bold">Kategori Temuan:</span><br>
                <span class="checkbox">{{ ($ltk->kategori_temuan == 'Fatality') ? '☑' : '☐' }}</span> Fatality
                <span class="checkbox">{{ ($ltk->kategori_temuan == 'Major') ? '☑' : '☐' }}</span> Major
                <span class="checkbox">{{ ($ltk->kategori_temuan == 'Minor') ? '☑' : '☐' }}</span> Minor
                <span class="checkbox">{{ ($ltk->kategori_temuan == 'Observasi') ? '☑' : '☐' }}</span> Obs
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
                ( ..................................... )
            </td>
        </tr>
    </table>

    @if($ltk->dilanjutkan_ke_ltk_no)
    <div style="margin-top: 2px; font-size: 7pt;">
        <em>* Dilanjutkan ke LTK No: {{ $ltk->dilanjutkan_ke_ltk_no }}</em>
    </div>
    @endif

</body>
</html>