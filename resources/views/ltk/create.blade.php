<!DOCTYPE html>
<html>
<head>
    <title>Input LTK Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-section { background: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; margin-bottom: 20px; border-radius: 5px; }
        .section-title { font-weight: bold; margin-bottom: 10px; color: #dc3545; text-transform: uppercase; border-bottom: 2px solid #dc3545; padding-bottom: 5px; }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <h2 class="mb-4 text-center">Form Input Laporan Temuan Ketidaksesuaian</h2>
        
        <form action="{{ route('ltk.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <div class="section-title">Informasi Dokumen</div>
                <div class="row">
                    <div class="col-md-3 mb-2"><label>No LTK</label><input type="text" name="LTK_No" class="form-control" placeholder="Contoh: 001/IA/2026"></div>
                    <div class="col-md-3 mb-2"><label>Tipe Audit</label>
                        <select name="Tipe_Audit_INT_OT" class="form-control">
                            <option value="Internal">Internal</option>
                            <option value="Eksternal">Eksternal</option>
                            <option value="Other">Lainnya</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2"><label>No Prosedur</label><input type="text" name="Nomor_Prosedur" class="form-control"></div>
                    <div class="col-md-3 mb-2"><label>Tanggal Audit</label><input type="date" name="Tanggal_Audit" class="form-control"></div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">Penerbit & Tujuan</div>
                <div class="row">
                    <div class="col-md-4 mb-2"><label>Kepada (Auditee)</label><input type="text" name="Kepada" class="form-control"></div>
                    <div class="col-md-4 mb-2"><label>Departemen/Proses</label><input type="text" name="Dept_Proses" class="form-control"></div>
                    <div class="col-md-4 mb-2"><label>Tanggal Laporan</label><input type="date" name="Tanggal_Laporan" class="form-control"></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-4"><label>Penerbit 1 (Auditor)</label><input type="text" name="Penerbit_1" class="form-control"></div>
                    <div class="col-md-4"><label>Penerbit 2</label><input type="text" name="Penerbit_2" class="form-control"></div>
                    <div class="col-md-4"><label>Penerbit 3</label><input type="text" name="Penerbit_3" class="form-control"></div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">Uraian Ketidaksesuaian</div>
                <div class="mb-2">
                    <label>Gambaran Ketidaksesuaian (PLOR)</label>
                    <textarea name="Gambaran_Ketidaksesuaian" class="form-control" rows="3"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2"><label>Lokasi</label><input type="text" name="Lokasi" class="form-control"></div>
                    <div class="col-md-6 mb-2"><label>Sumber Temuan</label><input type="text" name="Sumber_Temuan" class="form-control" placeholder="Audit, Patrol, dll"></div>
                </div>
                <div class="mb-2">
                    <label>Bukti Objektif</label>
                    <textarea name="Bukti_Objektif" class="form-control" rows="2"></textarea>
                </div>
                
                <label class="fw-bold mt-2">Referensi Standar (Centang yang sesuai):</label>
                <div class="row">
                    <div class="col-md-3"><input type="checkbox" name="Ref_ISO_9001" value="1"> ISO 9001</div>
                    <div class="col-md-3"><input type="checkbox" name="Ref_ISO_14001" value="1"> ISO 14001</div>
                    <div class="col-md-3"><input type="checkbox" name="Ref_SMK3" value="1"> SMK3</div>
                    <div class="col-md-3"><input type="checkbox" name="Ref_ISO_45001" value="1"> ISO 45001</div>
                    <div class="col-md-3"><input type="checkbox" name="Ref_LAB_17025" value="1"> ISO 17025</div>
                    <div class="col-md-3"><input type="checkbox" name="Ref_ISO_50001" value="1"> ISO 50001</div>
                    <div class="col-md-3"><input type="checkbox" name="Ref_SMKP_Minerba" value="1"> SMKP Minerba</div>
                    <div class="col-md-3"><input type="checkbox" name="Ref_ISO_37001" value="1"> ISO 37001</div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">Perbaikan & Pencegahan</div>
                <div class="mb-2">
                    <label>Akar Masalah (Root Cause Analysis)</label>
                    <textarea name="Akar_Masalah" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-2">
                    <label>Rencana Tindakan Perbaikan</label>
                    <textarea name="Rencana_Tindakan" class="form-control" rows="3"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-4"><label>Target Selesai</label><input type="date" name="Tgl_Selesai_Tindakan" class="form-control"></div>
                    <div class="col-md-4"><label>Nama Auditee (Penanggung Jawab)</label><input type="text" name="Auditee_Nama" class="form-control"></div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">Verifikasi Auditor</div>
                <div class="mb-2">
                    <label>Komentar Verifikasi</label>
                    <textarea name="Komentar_Verifikasi" class="form-control" rows="2"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-2"><label>Tgl Verifikasi</label><input type="date" name="Tgl_Verifikasi" class="form-control"></div>
                    <div class="col-md-3 mb-2"><label>Status Temuan</label>
                        <select name="Status_Temuan" class="form-control">
                            <option value="Open">Open</option>
                            <option value="Closed">Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2"><label>Kategori</label>
                        <select name="Kategori_Temuan" class="form-control">
                            <option value="Major">Major</option>
                            <option value="Minor">Minor</option>
                            <option value="Observasi">Observasi</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2"><label>Lanjut ke No LTK (Jika ada)</label><input type="text" name="Lanjut_Ke_LTK_No" class="form-control"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-5">Simpan Laporan</button>
        </form>
    </div>
</body>
</html>