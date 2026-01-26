<!DOCTYPE html>
<html>
<head>
    <title>Input LTK Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-section { background:#f8f9fa; padding:15px; border:1px solid #dee2e6; margin-bottom:20px; border-radius:5px; }
        .section-title { font-weight:bold; color:#b02a37; border-bottom:2px solid #b02a37; margin-bottom:10px; }
    </style>
</head>
<body class="p-4">
<div class="container">
<h4 class="text-center mb-4">FORM LAPORAN TEMUAN KETIDAKSESUAIAN (LTK)</h4>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<form method="POST" action="{{ route('ltk.store') }}">
@csrf

{{-- A. IDENTITAS --}}
<div class="form-section">
<div class="section-title">Identitas Dokumen</div>
<div class="row">
    <div class="col-md-3"><label>No LTK</label><input name="LTK_No" class="form-control"></div>
    <div class="col-md-3"><label>Status Dokumen</label>
        <select name="Status_Dokumen" class="form-control">
            <option>Baru</option><option>Revisi</option>
        </select>
    </div>
    <div class="col-md-3"><label>Tipe Audit</label>
        <select name="Tipe_Audit_INT_OT" class="form-control">
            <option>Internal</option><option>Eksternal</option><option>Other</option>
        </select>
    </div>
    <div class="col-md-3"><label>Tanggal Audit</label><input type="date" name="Tanggal_Audit" class="form-control"></div>
</div>
</div>

{{-- B. TUJUAN --}}
<div class="form-section">
<div class="section-title">Tujuan & Penerbit</div>
<div class="row">
    <div class="col-md-4"><label>Kepada</label><input name="Kepada" class="form-control"></div>
    <div class="col-md-4"><label>Dept / Proses</label><input name="Dept_Proses" class="form-control"></div>
    <div class="col-md-4"><label>Tanggal Laporan</label><input type="date" name="Tanggal_Laporan" class="form-control"></div>
</div>
</div>

{{-- C. SUMBER TEMUAN --}}
<div class="form-section">
<div class="section-title">Sumber Temuan</div>
<div class="row">
    <div class="col-md-4"><input type="checkbox" name="Sumber_Audit_Internal"> Audit Internal</div>
    <div class="col-md-4"><input type="checkbox" name="Sumber_SMST"> SMST</div>
    <div class="col-md-4"><input type="checkbox" name="Sumber_Komplain_Pelanggan"> Komplain</div>
    <div class="col-md-4"><input type="checkbox" name="Sumber_Proses_Perbaikan"> Proses Perbaikan</div>
    <div class="col-md-4"><input type="checkbox" name="Sumber_Tinjauan_Manajemen"> Tinjauan Manajemen</div>
    <div class="col-md-4"><input type="checkbox" name="Sumber_Lainnya"> Lainnya</div>
</div>
<input class="form-control mt-2" name="Sumber_Lainnya_Text" placeholder="Jika lainnya, sebutkan">
</div>

{{-- D. URAIAN --}}
<div class="form-section">
<div class="section-title">Uraian Temuan</div>
<textarea name="Gambaran_Ketidaksesuaian" class="form-control mb-2" rows="3"></textarea>
<input name="Lokasi" class="form-control mb-2" placeholder="Lokasi">
<textarea name="Bukti_Objektif" class="form-control" rows="2"></textarea>
</div>

{{-- E. REFERENSI --}}
<div class="form-section">
<div class="section-title">Referensi Standar</div>
<div class="row">
    <div class="col-md-3"><input type="checkbox" name="Ref_ISO_9001"> ISO 9001</div>
    <div class="col-md-3"><input name="ISO_9001_Klausul" class="form-control" placeholder="Klausul"></div>
</div>
</div>

{{-- F. TINDAKAN --}}
<div class="form-section">
<div class="section-title">Tindakan Perbaikan</div>
<textarea name="Akar_Masalah" class="form-control mb-2" rows="2" placeholder="Akar Masalah"></textarea>
<textarea name="Rencana_Tindakan_Perbaikan" class="form-control mb-2" rows="2"></textarea>
<input type="date" name="Tanggal_Selesai_Tindakan" class="form-control">
</div>

{{-- G. VERIFIKASI --}}
<div class="form-section">
<div class="section-title">Verifikasi</div>
<textarea name="Komentar_Verifikasi" class="form-control mb-2"></textarea>
<input type="date" name="Tanggal_Verifikasi" class="form-control mb-2">
<select name="Status_Temuan" class="form-control mb-2">
    <option>Selesai</option><option>Lanjut</option>
</select>
<select name="Kategori_Temuan" class="form-control mb-2">
    <option>Fatality</option><option>Major</option><option>Minor</option><option>Observasi</option>
</select>
</div>

<button class="btn btn-danger w-100 btn-lg">SIMPAN LTK</button>

</form>
</div>
</body>
</html>
