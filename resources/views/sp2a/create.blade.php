@extends('layouts.app')

@section('content')
<style>
    /* Styling agar editor terlihat tinggi seperti kertas surat */
    .ck-editor__editable_inline {
        min-height: 400px;
        padding: 2rem !important;
        border: 1px solid #ccc !important;
    }
</style>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Buat SP2A Baru (Sistem Role User)</h5>
            <small class="text-white-50">Admin Panel</small>
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
                        <div class="form-text">Penerima utama surat peringatan.</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Dari (Pengirim)</label>
                        <input type="text" name="dari_nama" class="form-control" placeholder="Misal: Kepala Departemen Audit Internal" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="Surat Peringatan 2A" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Dasar Penerbitan Surat / Temuan</label>
                    <textarea name="dasar_surat" class="form-control" rows="2" placeholder="Contoh: Berdasarkan temuan LKT No. 001/AUDIT/2026 tanggal 20 Januari..." required></textarea>
                </div>

                <div class="mb-4">
                     <label class="form-label fw-bold">Isi Surat Lengkap</label>
                     <textarea id="editor" name="isi_surat">
                        <p>Dengan hormat,</p>
                        <p>Sehubungan dengan temuan ketidaksesuaian yang belum ditindaklanjuti, maka kami sampaikan hal-hal berikut:</p>
                        <ul>
                            <li>Detail Temuan 1...</li>
                            <li>Detail Temuan 2...</li>
                        </ul>
                        <p>Mohon segera melakukan perbaikan sebelum tanggal yang ditentukan.</p>
                     </textarea>
                     <div class="form-text text-muted">
                        Tips: Anda bisa <strong>Copy-Paste tabel</strong> langsung dari Microsoft Word ke sini.
                     </div>
                </div>
                
                <hr>

                <h6 class="fw-bold mb-3">Tembusan (CC) ke Akun Terdaftar:</h6>
                
                <div class="row g-3 bg-light p-3 rounded border mb-4">
                    <div class="col-md-6">
                        <label class="fw-bold small text-muted">Auditor</label>
                        <select name="email_auditor" class="form-select form-select-sm">
                            <option value="">- Tidak Ada -</option>
                            @foreach($auditors as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold small text-muted">Staff K3 (Approver)</label>
                        <select name="email_k3" class="form-select form-select-sm">
                            <option value="">- Tidak Ada -</option>
                            @foreach($k3s as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold small text-muted">Staff Unit</label>
                        <select name="email_staff" class="form-select form-select-sm">
                            <option value="">- Tidak Ada -</option>
                            @foreach($staffs as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold small text-muted">Atasan Staff</label>
                        <select name="email_atasan" class="form-select form-select-sm">
                            <option value="">- Tidak Ada -</option>
                            @foreach($atasans as $u) <option value="{{ $u->email }}">{{ $u->name }}</option> @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 offset-md-3 text-center">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <label class="fw-bold mb-2">Nama Penanda Tangan (Di Surat)</label>
                                <input type="text" name="penanda_tangan_nama" class="form-control text-center fw-bold" placeholder="Nama Manager / Auditor Utama" required>
                                <small class="text-muted d-block mt-1">Nama ini akan muncul di bagian bawah surat PDF</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-5">
                    <a href="{{ route('sp2a.index') }}" class="btn btn-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Draft
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [ 
                'heading', '|', 
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', 'undo', 'redo'
            ],
            language: 'id'
        })
        .then(editor => {
            console.log('Editor berhasil dimuat');
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection