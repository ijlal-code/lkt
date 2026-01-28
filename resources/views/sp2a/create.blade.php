@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Buat SP2A (Sistem Role User)</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('sp2a.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-danger">Kepada (Auditi)</label>
                        <select name="kepada_user_id" class="form-select" required>
                            <option value="">-- Pilih Akun Auditi --</option>
                            @foreach($audities as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Dari (Pengirim)</label>
                        <input type="text" name="dari_nama" class="form-control" placeholder="Misal: Kepala Audit Internal" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="Surat Peringatan 2A" required>
                    </div>
                </div>

                <div class="mb-3">
                     <textarea id="editor" name="isi_surat">...</textarea>
                </div>
                
                <hr>
                <h6 class="fw-bold mb-3">Tembusan (CC) ke Akun Terdaftar:</h6>
                
                <div class="row g-3 bg-light p-3 rounded border">
                    <div class="col-md-6">
                        <label>Auditor</label>
                        <select name="email_auditor" class="form-select">
                            <option value="">- Tidak Ada -</option>
                            @foreach($auditors as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Staff K3</label>
                        <select name="email_k3" class="form-select">
                            <option value="">- Tidak Ada -</option>
                            @foreach($k3s as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Staff Unit</label>
                        <select name="email_staff" class="form-select">
                            <option value="">- Tidak Ada -</option>
                            @foreach($staffs as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Atasan Staff</label>
                        <select name="email_atasan" class="form-select">
                            <option value="">- Tidak Ada -</option>
                            @foreach($atasans as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <label>Nama Penanda Tangan</label>
                    <input type="text" name="penanda_tangan_nama" class="form-control w-50 mx-auto text-center" required>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('sp2a.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-danger">Simpan Draft</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection