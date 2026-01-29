@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-warning text-dark p-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Revisi SP2A</h4>
        </div>
        <div class="card-body p-4">
            
            {{-- BAGIAN CATATAN KOREKSI (PENTING UNTUK REVISI) --}}
            @if($sp2a->catatan_koreksi)
                <div class="alert alert-danger border-danger mb-4">
                    <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Catatan Perbaikan dari Atasan:</h5>
                    <p class="mb-0 fs-5 fst-italic">"{{ $sp2a->catatan_koreksi }}"</p>
                </div>
            @endif

            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('sp2a.update', $sp2a->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Method PUT untuk Update --}}

                <div class="row g-3">
                    {{-- Bagian Penerima (Auditi) --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Pilih Auditi (Penerima)</label>
                        <select name="kepada_user_id" id="kepada_user_id" class="form-select @error('kepada_user_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Nama Auditi --</option>
                            @foreach($audities as $auditi)
                                <option value="{{ $auditi->id }}" 
                                    data-email="{{ $auditi->email }}"
                                    {{-- Cek apakah ini auditi yang tersimpan sebelumnya --}}
                                    {{ (old('kepada_user_id') == $auditi->id || $sp2a->kepada_email == $auditi->email) ? 'selected' : '' }}>
                                    {{ $auditi->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Auditi</label>
                        {{-- Email otomatis terisi via JS --}}
                        <input type="email" name="kepada_email" id="kepada_email" class="form-control bg-light" value="{{ old('kepada_email', $sp2a->kepada_email) }}" readonly required>
                    </div>

                    {{-- Metadata Pengirim --}}
                    <input type="hidden" name="dari_nama" value="{{ Auth::user()->name }}">

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $sp2a->tanggal_surat->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="{{ old('perihal', $sp2a->perihal) }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Dasar Surat</label>
                        <input type="text" name="dasar_surat" class="form-control" value="{{ old('dasar_surat', $sp2a->dasar_surat) }}" placeholder="Contoh: LKT/2026/001" required>
                    </div>

                    {{-- Isi Surat dengan CKEditor --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Isi Surat</label>
                        <textarea name="isi_surat" id="isi_surat" class="form-control @error('isi_surat') is-invalid @enderror">{{ old('isi_surat', $sp2a->isi_surat) }}</textarea>
                    </div>

                    {{-- Tembusan (CC) --}}
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted">CC Auditor</label>
                        <input type="email" name="email_auditor" class="form-control" value="{{ old('email_auditor', $sp2a->email_auditor) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted">CC K3</label>
                        <input type="email" name="email_k3" class="form-control" value="{{ old('email_k3', $sp2a->email_k3) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted">CC Staff Admin</label>
                        <input type="email" name="email_staff" class="form-control" value="{{ old('email_staff', $sp2a->email_staff) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted">CC Atasan</label>
                        <input type="email" name="email_atasan" class="form-control" value="{{ old('email_atasan', $sp2a->email_atasan) }}">
                    </div>

                    {{-- Penanda Tangan --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-primary">Nama Penanda Tangan (Final GM)</label>
                        <input type="text" name="penanda_tangan_nama" class="form-control border-primary" value="{{ old('penanda_tangan_nama', $sp2a->penanda_tangan_nama) }}" required>
                    </div>
                </div>

                <div class="mt-4 text-end border-top pt-3">
                    <a href="{{ route('sp2a.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-warning px-5 py-2 shadow-sm fw-bold">
                        <i class="bi bi-send-check me-2"></i>KIRIM REVISI KE SM
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
    document.getElementById('kepada_user_id').addEventListener('change', function() {
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