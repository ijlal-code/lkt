<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan LTK - {{ $ltk->LTK_No }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Support untuk simbol checklist */
            font-size: 11px;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }
        .no-border { border: none !important; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .header-logo { width: 60px; height: auto; }
        .section-title {
            background-color: #eee;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px;
        }
        .checkbox-item { display: inline-block; margin-right: 15px; }
        .signature-box { height: 60px; vertical-align: bottom; text-align: center; }
        .field-label { font-weight: bold; font-size: 10px; color: #333; }
        .field-value { margin-bottom: 4px; }
    </style>
</head>
<body>

    <table>
        <tr>
            <td class="text-center" width="15%">
                <img src="{{ public_path('img/logo-tonasa.png') }}" class="header-logo" alt="Logo Tonasa">
            </td>
            <td class="text-center" width="70%">
                <h2 style="margin:5px 0;">PT SEMEN TONASA</h2>
                <h3 style="margin:0;">{{ $ltk->Judul_Dokumen ?? 'LAPORAN TEMUAN KETIDAKSESUAIAN (LTK)' }}</h3>
            </td>
            <td class="text-center" width="15%">
                <img src="{{ public_path('img/logo-internal-audit.png') }}" class="header-logo" alt="Logo IA">
                <br>
                <small>{{ $ltk->Kode_Form ?? 'FRM-IA-01' }}</small>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="50%">
                <div class="field-label">LTK No:</div>
                <div class="field-value">{{ $ltk->LTK_No }}</div>
            </td>
            <td width="50%">
                <div class="field-label">Tanggal Audit:</div>
                <div class="field-value">{{ $ltk->Tanggal_Audit }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Tipe Audit:</div>
                <div class="field-value">{{ $ltk->Tipe_Audit_INT_OT }}</div>
            </td>
            <td>
                <div class="field-label">Nomor Prosedur:</div>
                <div class="field-value">{{ $ltk->Nomor_Prosedur }} (Tahun: {{ $ltk->Tahun }})</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Kepada (Auditee):</div>
                <div class="field-value">{{ $ltk->Kepada }}</div>
                <div class="field-label">Dept/Proses:</div>
                <div class="field-value">{{ $ltk->Dept_Proses }}</div>
            </td>
            <td>
                <div class="field-label">Auditor (Penerbit):</div>
                <ol style="margin: 0; padding-left: 15px;">
                    @if($ltk->Penerbit_1) <li>{{ $ltk->Penerbit_1 }}</li> @endif
                    @if($ltk->Penerbit_2) <li>{{ $ltk->Penerbit_2 }}</li> @endif
                    @if($ltk->Penerbit_3) <li>{{ $ltk->Penerbit_3 }}</li> @endif
                </ol>
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="section-title">1. Uraian Ketidaksesuaian</td></tr>
        <tr>
            <td colspan="2">
                <div class="field-label">Sumber Temuan:</div>
                <div>
                    {{ $ltk->Sumber_Temuan }} 
                    @if($ltk->Sumber_Lainnya_Text) ({{ $ltk->Sumber_Lainnya_Text }}) @endif
                </div>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="2">
                <div class="field-label">Gambaran Ketidaksesuaian:</div>
                <div style="min-height: 60px;">{{ $ltk->Gambaran_Ketidaksesuaian }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Lokasi:</div>
                <div>{{ $ltk->Lokasi }}</div>
            </td>
            <td>
                <div class="field-label">Bukti Objektif / Foto:</div>
                <div>{{ $ltk->Bukti_Objektif ?? $ltk->Bukti_Objektif_Foto }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="field-label">Referensi Standar:</div>
                <div style="font-size: 10px;">
                    <span class="checkbox-item">{{ $ltk->Ref_ISO_9001 ? '☑' : '☐' }} ISO 9001</span>
                    <span class="checkbox-item">{{ $ltk->Ref_ISO_14001 ? '☑' : '☐' }} ISO 14001</span>
                    <span class="checkbox-item">{{ $ltk->Ref_SMK3 ? '☑' : '☐' }} SMK3</span>
                    <span class="checkbox-item">{{ $ltk->Ref_ISO_45001 ? '☑' : '☐' }} ISO 45001</span>
                    <br>
                    <span class="checkbox-item">{{ $ltk->Ref_LAB_17025 ? '☑' : '☐' }} ISO/IEC 17025</span>
                    <span class="checkbox-item">{{ $ltk->Ref_ISO_50001 ? '☑' : '☐' }} ISO 50001</span>
                    <span class="checkbox-item">{{ $ltk->Ref_SMKP_Minerba ? '☑' : '☐' }} SMKP Minerba</span>
                    <span class="checkbox-item">{{ $ltk->Ref_ISO_37001 ? '☑' : '☐' }} ISO 37001</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="text-center signature-box">
                <br><br>
                ( {{ $ltk->Paraf_Auditor }} )<br>
                <strong>Paraf Auditor</strong>
            </td>
            <td class="text-center signature-box">
                <br><br>
                ( {{ $ltk->Inisial_Auditor }} )<br>
                <strong>Inisial</strong>
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="section-title">2. Analisa Penyebab & Rencana Tindakan</td></tr>
        <tr>
            <td colspan="2">
                <div class="field-label">Akar Masalah (Root Cause):</div>
                <div style="min-height: 50px;">{{ $ltk->Akar_Masalah }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="field-label">Rencana Tindakan Perbaikan:</div>
                <div style="min-height: 50px;">{{ $ltk->Rencana_Tindakan }}</div>
            </td>
        </tr>
        <tr>
            <td width="70%">
                <div class="field-label">Auditee (Nama & Paraf):</div>
                <br>
                Nama: {{ $ltk->Auditee_Nama }} <span style="margin-left:20px;">Paraf: {{ $ltk->Paraf_Auditee }}</span>
            </td>
            <td width="30%">
                <div class="field-label">Target Selesai:</div>
                <div>{{ $ltk->Tgl_Selesai_Tindakan }}</div>
            </td>
        </tr>
    </table>

    <table>
        <tr><td colspan="2" class="section-title">3. Verifikasi Tindakan Perbaikan</td></tr>
        <tr>
            <td colspan="2">
                <div class="field-label">Komentar Verifikasi:</div>
                <div style="min-height: 50px;">{{ $ltk->Komentar_Verifikasi }}</div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="field-label">Status Temuan:</div>
                <span class="checkbox-item">{{ $ltk->Status_Temuan == 'Open' ? '☑' : '☐' }} Open</span>
                <span class="checkbox-item">{{ $ltk->Status_Temuan == 'Closed' ? '☑' : '☐' }} Closed</span>
            </td>
            <td>
                <div class="field-label">Kategori Temuan:</div>
                <span class="checkbox-item">{{ $ltk->Kategori_Temuan == 'Major' ? '☑' : '☐' }} Major</span>
                <span class="checkbox-item">{{ $ltk->Kategori_Temuan == 'Minor' ? '☑' : '☐' }} Minor</span>
                <span class="checkbox-item">{{ $ltk->Kategori_Temuan == 'Observasi' ? '☑' : '☐' }} Observasi</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="no-border" style="margin:0;">
                    <tr>
                        <td class="no-border" width="33%">
                            <div class="field-label">Tanggal Verifikasi:</div>
                            {{ $ltk->Tgl_Verifikasi }}
                        </td>
                        <td class="no-border text-center" width="33%">
                            <div class="signature-box">
                                ( {{ $ltk->Penutup_Penerbit_Paraf }} )<br>
                                <strong>Paraf Auditor</strong>
                            </div>
                        </td>
                        <td class="no-border text-center" width="33%">
                             <div class="signature-box">
                                ( {{ $ltk->Penutup_Penerbit_Inisial }} )<br>
                                <strong>Inisial</strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($ltk->Lanjut_Ke_LTK_No)
    <div style="text-align: right; font-style: italic;">
        Dilanjutkan ke LTK No: {{ $ltk->Lanjut_Ke_LTK_No }}
    </div>
    @endif

</body>
</html>