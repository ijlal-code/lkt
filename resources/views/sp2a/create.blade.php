@extends('layouts.app')

@section('content')
<style>
    /* Mengatur tinggi editor CKEditor agar mirip dengan kertas surat */
    .ck-editor__editable_inline {
        min-height: 400px;
        padding: 2rem !important; /* Memberi jarak teks seperti kertas */
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Form Surat Peringatan 2A</h5>
                    <small>Editor: CKEditor 5 (Gratis & Tanpa API)</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('sp2a.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Surat</label>
                                <input type="text" class="form-control bg-light" value="(Otomatis saat Approved)" readonly disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kepada Yth (Auditee)</label>
                                <select name="kepada_id" class="form-select" required>
                                    <option value="">-- Pilih Tujuan dari Kontak --</option>
                                    @foreach($contacts as $c)
                                        <option value="{{ $c->id }}">{{ $c->nama }} ({{ $c->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Dari (Pengirim)</label>
                                <input type="text" name="dari_nama" class="form-control" placeholder="Misal: Kepala Departemen Audit" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Perihal</label>
                            <input type="text" name="perihal" class="form-control" value="Surat Peringatan 2A" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Dasar Penerbitan Surat</label>
                            <textarea name="dasar_surat" class="form-control" rows="2" placeholder="Contoh: Berdasarkan temuan audit No. LKT/001..."></textarea>
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
                                Tips: Anda bisa langsung <strong>Copy-Paste</strong> teks panjang atau tabel dari Microsoft Word ke sini.
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3 mt-4">
                            <div class="col-md-6 offset-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <h6 class="fw-bold mb-5">Tanda Tangan</h6>
                                        <br>
                                        
                                        <div class="mb-1">
                                            <input type="text" name="penanda_tangan_nama" class="form-control text-center fw-bold" placeholder="(Nama Lengkap)" required>
                                        </div>
                                        <small class="text-muted">Jabatan Penanda Tangan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 p-3 border rounded bg-light">
                            <label class="form-label fw-bold">Tembusan / CC</label>
                            <select name="cc_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($contacts as $c)
                                    <option value="{{ $c->id }}">{{ $c->nama }} ({{ $c->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('sp2a.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-danger px-4">Simpan Draft</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            // Opsi toolbar agar lebih ringkas (Optional)
            toolbar: [ 
                'heading', '|', 
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', 'undo', 'redo'
            ],
            language: 'id' // Bahasa (jika didukung browser)
        })
        .then(editor => {
            console.log('Editor berhasil dimuat', editor);
        })
        .catch(error => {
            console.error('Ada masalah saat memuat editor:', error);
        });
</script>
@endsection