<!DOCTYPE html>
<html>
<head>
    <title>LTK - {{ $ltk->LTK_No }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        td, th { border: 1px solid black; padding: 5px; vertical-align: top; }
        
        .no-border { border: none !important; }
        .no-border td { border: none !important; }
        
        .header-title { font-weight: bold; font-size: 14pt; text-align: center; }
        .header-sub { font-size: 8pt; text-align: center; }
        
        .bg-gray { background-color: #f0f0f0; font-weight: bold; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        
        .check-box { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; margin-right: 3px; text-align: center; line-height: 10px; font-size: 8px; }
        .checked { background-color: #000; color: #fff; }

        .signature-box { height: 60px; }
    </style>
</head>
<body>

    <table style="border: 2px solid black;">
        <tr>
            <td width="15%" class="text-center" style="vertical-align: middle;">
                <img src="{{ public_path('img/logo-tonasa.png') }}" width="60">
            </td>
            <td width="70%" class="text-center" style="vertical-align: middle;">
                <div class="header-title">PT SEMEN TONASA</div>
                <div class="header-sub">UNIT INTERNAL AUDIT</div>
                <div style="font-size: 12pt; font-weight: bold; margin-top: 5px;">LAPORAN TEMUAN KETIDAKSESUAIAN (LTK)</div>
            </td>
            <td width="15%" class="text-center" style="vertical-align: middle;">
                <img src="{{ public_path('img/logo-internal-audit.png') }}" width="60">
                <br><span style="font-size: 8pt;">{{ $ltk->Kode_Form }}</span>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="20%" class="bg-gray">LTK No.</td>
            <td width="30%">{{ $ltk->LTK_No }}</td>
            <td width="20%" class="bg-gray">Tipe Audit</td>
            <td width="30%">{{ $ltk->Tipe_Audit_INT_OT }}</td>
        </tr>
        <tr>
            <td class="bg-gray">No. Prosedur</td>
            <td>{{ $ltk->Nomor_Prosedur }}</td>
            <td class="bg-gray">Tanggal Audit</td>
            <td>{{ $ltk->Tanggal_Audit ? \Carbon\Carbon::parse($ltk->Tanggal_Audit)->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="15%" class="bg-gray">Kepada</td>
            <td width="35%">{{ $ltk->Kepada }}</td>
            <td width="15%" class="bg-gray">Penerbit (Auditor)</td>
            <td width="35%">
                1. {{ $ltk->Penerbit_1 }}<br>
                2. {{ $ltk->Penerbit_2 }}
            </td>
        </tr>
        <tr>
            <td class="bg-gray">Dept / Proses</td>
            <td>{{ $ltk->Dept_Proses }}</td>
            <td class="bg-gray">Tanggal Laporan</td>
            <td>{{ $ltk->Tanggal_Laporan ? \Carbon\Carbon::parse($ltk->Tanggal_Laporan)->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4" class="bg-gray text-center">URAIAN KETIDAKSESUAIAN</td>
        </tr>
        <tr>
            <td width="15%" class="text-bold">Gambaran</td>
            <td colspan="3" style="height: 60px;">{{ $ltk->Gambaran_Ketidaksesuaian }}</td>
        </tr>
        <tr>
            <td class="text-bold">Bukti Objektif</td>
            <td colspan="3">{{ $ltk->Bukti_Objektif }}</td>
        </tr>
        <tr>
            <td class="text-bold">Lokasi</td>
            <td>{{ $ltk->Lokasi }}</td>
            <td class="text-bold">Sumber Temuan</td>
            <td>{{ $ltk->Sumber_Temuan }}</td>
        </tr>
        <tr>
            <td class="text-bold">Ref. Standar</td>
            <td colspan="3" style="font-size: 9pt;">
                <span class="check-box {{ $ltk->Ref_ISO_9001 ? 'checked' : '' }}"></span> ISO 9001 &nbsp;
                <span class="check-box {{ $ltk->Ref_ISO_14001 ? 'checked' : '' }}"></span> ISO 14001 &nbsp;
                <span class="check-box {{ $ltk->Ref_SMK3 ? 'checked' : '' }}"></span> SMK3 &nbsp;
                <span class="check-box {{ $ltk->Ref_ISO_45001 ? 'checked' : '' }}"></span> ISO 45001 &nbsp;
                <span class="check-box {{ $ltk->Ref_LAB_17025 ? 'checked' : '' }}"></span> ISO 17025 &nbsp;
                <span class="check-box {{ $ltk->Ref_ISO_50001 ? 'checked' : '' }}"></span> ISO 50001 &nbsp;
                <span class="check-box {{ $ltk->Ref_SMKP_Minerba ? 'checked' : '' }}"></span> SMKP &nbsp;
                <span class="check-box {{ $ltk->Ref_ISO_37001 ? 'checked' : '' }}"></span> ISO 37001
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                Disetujui Auditor:<br>
                <div class="signature-box"></div>
                <b>{{ $ltk->Penerbit_1 }}</b>
            </td>
            <td colspan="2" class="text-center">
                Diterima Auditee:<br>
                <div class="signature-box"></div>
                <b>{{ $ltk->Auditee_Nama ?? '(...................)' }}</b>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2" class="bg-gray text-center">RENCANA PERBAIKAN (Diisi oleh Auditee)</td>
        </tr>
        <tr>
            <td width="20%" class="text-bold">Akar Masalah</td>
            <td style="height: 50px;">{{ $ltk->Akar_Masalah }}</td>
        </tr>
        <tr>
            <td class="text-bold">Tindakan Perbaikan</td>
            <td style="height: 50px;">{{ $ltk->Rencana_Tindakan }}</td>
        </tr>
        <tr>
            <td class="text-bold">Target Selesai</td>
            <td>{{ $ltk->Tgl_Selesai_Tindakan ? \Carbon\Carbon::parse($ltk->Tgl_Selesai_Tindakan)->format('d-m-Y') : '-' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="4" class="bg-gray text-center">VERIFIKASI PENYELESAIAN (Diisi oleh Auditor)</td>
        </tr>
        <tr>
            <td colspan="4" style="height: 50px;">
                <b>Komentar:</b><br>
                {{ $ltk->Komentar_Verifikasi }}
            </td>
        </tr>
        <tr>
            <td width="20%" class="text-bold">Status Temuan</td>
            <td width="30%">
                [{{ $ltk->Status_Temuan == 'Open' ? 'X' : ' ' }}] Open &nbsp;&nbsp;
                [{{ $ltk->Status_Temuan == 'Closed' ? 'X' : ' ' }}] Closed
            </td>
            <td width="20%" class="text-bold">Tanggal Verifikasi</td>
            <td width="30%">{{ $ltk->Tgl_Verifikasi ? \Carbon\Carbon::parse($ltk->Tgl_Verifikasi)->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td colspan="4" class="text-center">
                <br>
                Verifikator (Auditor):<br>
                <div class="signature-box"></div>
                <b>{{ $ltk->Penerbit_1 }}</b>
            </td>
        </tr>
    </table>

</body>
</html>