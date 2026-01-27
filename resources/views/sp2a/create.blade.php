@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Buat Draft SP2A</h4>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-info">
                        <strong>Info:</strong> Anda sedang membuat SP2A untuk Laporan Ketidaksesuaian Nomor: 
                        <span class="fw-bold">{{ $ltk->nomor_lkt }}</span>
                    </div>

                    <form action="{{ route('sp2a.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ltk_id" value="{{ $ltk->id }}">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal_sp2a" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kepada (Auditee/Unit)</label>
                                <input type="text" name="kepada" class="form-control" value="{{ $ltk->auditee_nama ?? $ltk->unit_kerja }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email Auditee <span class="text-danger">*</span></label>
                                <input type="email" name="email_auditee" class="form-control" placeholder="contoh@perusahaan.com" required>
                                <div class="form-text">Wajib diisi untuk pengiriman notifikasi otomatis.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Auditor (CC)</label>
                                <input type="email" name="email_auditor" class="form-control" placeholder="auditor@perusahaan.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dasar Peringatan (Diambil dari Ketidaksesuaian LKT)</label>
                            <textarea name="dasar_peringatan" class="form-control bg-light" rows="3" readonly>{{ $ltk->ketidaksesuaian }}</textarea>
                            <div class="form-text">Field ini otomatis terisi dari data LKT dan tidak dapat diedit di sini.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Isi Peringatan / Tindak Lanjut</label>
                            <textarea name="isi_peringatan" class="form-control" rows="5" required>Sehubungan dengan temuan ketidaksesuaian pada LKT {{ $ltk->nomor_lkt }} yang belum mendapatkan tindak lanjut yang memadai hingga batas waktu yang ditentukan, maka diterbitkan Surat Peringatan 2A ini.</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ltk.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-danger">Simpan Draft SP2A</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection