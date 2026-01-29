@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white p-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-file-earmark-plus me-2"></i>Buat SP2A Baru</h4>
        </div>
        <div class="card-body p-4">
            {{-- Tampilkan Error Validasi jika ada --}}
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
                    {{-- Bagian Penerima (Auditi) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pilih Auditi (Penerima)</label>
                        {{-- Pastikan name="kepada_nama" ada agar tidak null di controller --}}
                        <select name="kepada_nama" id="kepada_nama" class="form-select @error('kepada_nama') is-invalid @enderror" required>
                            <option value="">-- Pilih Nama Auditi --</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->name }}" data-email="{{ $contact->email }}">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Auditi</label>
                        {{-- Input email otomatis terisi via JS, dikirim via name="kepada_email" --}}
                        <input type="email" name="kepada_email" id="kepada_email" class="form-control bg-light @error('kepada_email') is-invalid @enderror" readonly required>
                    </div>

                    {{-- Metadata Pengirim --}}
                    <input type="hidden" name="dari_nama" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="email_staff" value="{{ Auth::user()->email }}">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="Surat Peringatan 2A" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Dasar Surat</label>
                        <input type="text" name="dasar_surat" class="form-control" placeholder="Contoh: LKT/2026/001" required>
                    </div>

                    {{-- Isi Surat dengan CKEditor --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Isi Surat</label>
                        <textarea name="isi_surat" id="isi_surat" class="form-control @error('isi_surat') is-invalid @enderror"></textarea>
                    </div>

                    {{-- Tembusan (CC) --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted">CC Auditor (Opsional)</label>
                        <input type="email" name="email_auditor" class="form-control" placeholder="auditor@example.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted">CC K3 (Opsional)</label>
                        <input type="email" name="email_k3" class="form-control" placeholder="k3@example.com">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted">CC Atasan (Opsional)</label>
                        <input type="email" name="email_atasan" class="form-control" placeholder="atasan@example.com">
                    </div>

                    {{-- Penanda Tangan --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-primary">Nama Penanda Tangan (Final GM)</label>
                        <input type="text" name="penanda_tangan_nama" class="form-control border-primary" placeholder="Masukkan Nama Lengkap GM Internal Audit" required>
                    </div>
                </div>

                <div class="mt-4 text-end border-top pt-3">
                    <button type="reset" class="btn btn-light me-2">Reset Form</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-bold">
                        <i class="bi bi-send-check me-2"></i>SIMPAN & KIRIM KE SM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script Pendukung --}}
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    // Inisialisasi CKEditor
    CKEDITOR.replace('isi_surat', {
        height: 300,
        removePlugins: 'elementspath',
        resize_enabled: false
    });

    // Script untuk mengisi email otomatis saat Nama Auditi dipilih
    document.getElementById('kepada_nama').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const email = selectedOption.getAttribute('data-email');
        const emailInput = document.getElementById('kepada_email');
        
        if (email) {
            emailInput.value = email;
        } else {
            emailInput.value = '';
        }
    });
</script>
@endsection