@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Input LKT (Sesuai Format PDF)</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('ltk.store') }}" method="POST">
                @csrf
                
                <h6 class="text-primary border-bottom pb-2 mb-3">I. Identitas & Sumber</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nomor LKT</label>
                        <input type="text" name="nomor_lkt" class="form-control" placeholder="Contoh: LKT-2026-001" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tanggal Laporan</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Penerbit 1</label>
                        <input type="text" name="penerbit_1" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Penerbit 2</label>
                        <input type="text" name="penerbit_2" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Penerbit 3</label>
                        <input type="text" name="penerbit_3" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Sumber Temuan</label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="sumber" class="form-select">
                                <option value="Audit Internal">Audit Internal (SKAI)</option>
                                <option value="SMST">SMST</option>
                                <option value="Komplain Pelanggan">Komplain Pelanggan</option>
                                <option value="Proses Perbaikan">Proses Perbaikan</option>
                                <option value="Tinjauan Manajemen">Tinjauan Manajemen</option>
                                <option value="Lainnya">Lainnya (Isi teks dibawah)</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="sumber_lainnya_text" class="form-control" placeholder="Keterangan jika sumber 'Lainnya'">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kepada (Nama)</label>
                        <input type="text" name="kepada" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Unit Kerja / Dept / Proses</label>
                        <input type="text" name="unit_kerja" class="form-control" required>
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">II. Uraian Ketidaksesuaian</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold">Uraian Ketidaksesuaian (PLOR)</label>
                    <textarea name="ketidaksesuaian" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Lokasi Temuan</label>
                        <input type="text" name="lokasi" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bukti Objektif</label>
                        <input type="text" name="bukti_objektif" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Inisial Auditor</label>
                    <input type="text" name="inisial_auditor" class="form-control" style="width: 150px;">
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">III. Referensi (Klausul/Elemen)</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small">ISO 9001:2015</label>
                        <input type="text" name="iso_9001_klausul" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">ISO 14001:2015</label>
                        <input type="text" name="iso_14001_klausul" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">SMK3 Elemen</label>
                        <input type="text" name="smk3_elemen" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">ISO 45001:2018</label>
                        <input type="text" name="iso_45001_klausul" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">LAB 17025:2017</label>
                        <input type="text" name="lab_17025_klausul" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">ISO 50001:2018</label>
                        <input type="text" name="iso_50001_klausul" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">SMKP Minerba</label>
                        <input type="text" name="smkp_minerba_elemen" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">ISO 37001:2016</label>
                        <input type="text" name="iso_37001_elemen" class="form-control form-control-sm">
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">IV. Analisa & Tindakan</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold">Akar Masalah (Root Cause)</label>
                    <textarea name="akar_penyebab" class="form-control" rows="2" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Rencana Tindakan Perbaikan</label>
                    <textarea name="tindakan_perbaikan" class="form-control" rows="2" required></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Auditee / Penanggung Jawab</label>
                        <input type="text" name="auditee_nama" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Target Penyelesaian</label>
                        <input type="date" name="target_penyelesaian" class="form-control" required>
                    </div>
                </div>

                <h6 class="text-primary border-bottom pb-2 mb-3 mt-4">V. Verifikasi (Opsional Saat Awal)</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Status Temuan</label>
                        <select name="status" class="form-select">
                            <option value="Lanjut">Lanjut (Open)</option>
                            <option value="Selesai">Selesai (Closed)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori Temuan</label>
                        <select name="kategori_temuan" class="form-select">
                            <option value="Major">Major</option>
                            <option value="Minor">Minor</option>
                            <option value="Observasi">Observasi</option>
                            <option value="Fatality">Fatality</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Komentar Verifikasi</label>
                    <textarea name="verifikasi_tindakan" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Dilanjutkan ke LTK No (Jika ada)</label>
                    <input type="text" name="dilanjutkan_ke_ltk_no" class="form-control">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('ltk.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan LKT</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection