@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white p-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-file-earmark-plus me-2"></i>Buat SP2A Baru</h4>
        </div>
        <div class="card-body p-4">

            {{-- Menampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sp2a.store') }}" method="POST">
                @csrf
                
                <div class="row g-3">
                    
                    {{-- HEADER: TANGGAL & PENGIRIM --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Dari (Pengirim)</label>
                        <input type="text" name="dari_nama" class="form-control" value="{{ Auth::user()->name }}" placeholder="Nama / Jabatan Pengirim" required>
                    </div>

                    <hr class="my-4">

                    {{-- TUJUAN (MANUAL INPUT TEKS) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-danger">Kepada (Nama Penerima)</label>
                        <input type="text" name="kepada_nama" class="form-control" placeholder="Masukkan Nama Lengkap Auditi / Unit" required>
                        <div class="form-text">Tuliskan nama tujuan surat secara manual.</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Penerima (Opsional)</label>
                        <input type="email" name="kepada_email" class="form-control" placeholder="email@contoh.com">
                        <div class="form-text">Digunakan untuk notifikasi (jika ada).</div>
                    </div>

                    {{-- DETAIL SURAT --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="Surat Peringatan 2A" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Dasar Surat / Temuan</label>
                        <input type="text" name="dasar_surat" class="form-control" placeholder="Contoh: Berdasarkan LKT No. 001/AUDIT/2026..." required>
                    </div>

                    {{-- ISI SURAT (CKEDITOR 5) --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Isi Surat Lengkap</label>
                        <textarea name="isi_surat" id="isi_surat" class="form-control">
<p>Dengan hormat,</p>
<p>Sehubungan dengan temuan ketidaksesuaian yang belum ditindaklanjuti, kami sampaikan peringatan sebagai berikut...</p>
                        </textarea>
                    </div>

                    {{-- PENANDA TANGAN (GM) --}}
                    <div class="col-md-12 mt-4">
                         <div class="p-3 bg-light border rounded">
                            <label class="form-label fw-bold text-primary">Nama Penanda Tangan (GM Internal Audit)</label>
                            <input type="text" name="penanda_tangan_nama" class="form-control border-primary" placeholder="Masukkan Nama GM Internal Audit" required>
                            <div class="form-text">Nama ini akan muncul di bagian tanda tangan surat setelah disetujui.</div>
                        </div>
                    </div>

                    {{-- TEMBUSAN (DYNAMIC LIST) --}}
                    <div class="col-12 mt-4">
                        <label class="form-label fw-bold">Tembusan (CC)</label>
                        <div class="form-text mb-2">Tambahkan daftar penerima tembusan surat ini (Input Manual).</div>
                        
                        <div id="tembusan-wrapper">
                            {{-- Baris Default Pertama --}}
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white">1.</span>
                                <input type="text" name="tembusan[]" class="form-control" placeholder="Contoh: Arsip Internal Audit">
                                <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-tembusan">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Baris Tembusan
                        </button>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2 mt-5 pt-3 border-top">
                    <a href="{{ route('sp2a.index') }}" class="btn btn-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold">
                        <i class="bi bi-send me-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- STYLE KHUSUS UNTUK CKEDITOR 5 HEIGHT --}}
<style>
    .ck-editor__editable {
        min-height: 300px;
    }
</style>

{{-- SCRIPT JAVASCRIPT --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<script>
    // 1. Inisialisasi CKEditor 5
    ClassicEditor
        .create(document.querySelector('#isi_surat'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
        })
        .catch(error => {
            console.error(error);
        });

    // 2. Script untuk Tembusan Dinamis
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('tembusan-wrapper');
        const btnAdd = document.getElementById('add-tembusan');

        // Fungsi Update Nomor Urut
        function updateNumbers() {
            const rows = wrapper.querySelectorAll('.input-group');
            rows.forEach((row, index) => {
                row.querySelector('.input-group-text').innerText = (index + 1) + '.';
            });
        }

        // Tambah Baris Baru
        btnAdd.addEventListener('click', function() {
            const count = wrapper.children.length + 1;
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <span class="input-group-text bg-white">${count}.</span>
                <input type="text" name="tembusan[]" class="form-control" placeholder="Tembusan lainnya...">
                <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
            `;
            wrapper.appendChild(div);
        });

        // Hapus Baris
        wrapper.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.input-group');
                if (wrapper.children.length > 1) {
                    row.remove();
                    updateNumbers();
                } else {
                    row.querySelector('input').value = '';
                }
            }
        });
    });
</script>
@endsection