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
                @method('PUT')

                <div class="row g-3">
                    
                    {{-- HEADER --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $sp2a->tanggal_surat->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Dari (Pengirim)</label>
                        <input type="text" name="dari_nama" class="form-control" value="{{ old('dari_nama', $sp2a->dari_nama) }}" required>
                    </div>

                    <hr class="my-4">

                    {{-- TUJUAN --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-danger">Kepada (Nama Penerima)</label>
                        <input type="text" name="kepada_nama" class="form-control" value="{{ old('kepada_nama', $sp2a->kepada_nama) }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Penerima (Opsional)</label>
                        <input type="email" name="kepada_email" class="form-control" value="{{ old('kepada_email', $sp2a->kepada_email) }}">
                    </div>

                    {{-- DETAIL --}}
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="{{ old('perihal', $sp2a->perihal) }}" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Dasar Surat / Temuan</label>
                        <input type="text" name="dasar_surat" class="form-control" value="{{ old('dasar_surat', $sp2a->dasar_surat) }}" required>
                    </div>

                    {{-- ISI SURAT (CKEDITOR 5) --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Isi Surat Lengkap</label>
                        <textarea name="isi_surat" id="isi_surat" class="form-control">{{ old('isi_surat', $sp2a->isi_surat) }}</textarea>
                    </div>

                    {{-- PENANDA TANGAN --}}
                    <div class="col-md-12 mt-4">
                         <div class="p-3 bg-light border rounded">
                            <label class="form-label fw-bold text-primary">Nama Penanda Tangan (GM Internal Audit)</label>
                            <input type="text" name="penanda_tangan_nama" class="form-control border-primary" value="{{ old('penanda_tangan_nama', $sp2a->penanda_tangan_nama) }}" required>
                        </div>
                    </div>

                    {{-- TEMBUSAN (LOOPING DATA LAMA) --}}
                    <div class="col-12 mt-4">
                        <label class="form-label fw-bold">Tembusan (CC)</label>
                        <div class="form-text mb-2">Silakan edit atau tambah daftar tembusan.</div>
                        
                        <div id="tembusan-wrapper">
                            @php
                                // Ambil data dari old input (jika error validasi) atau dari database
                                $listTembusan = old('tembusan', $sp2a->tembusan ?? []);
                            @endphp

                            @if(count($listTembusan) > 0)
                                @foreach($listTembusan as $index => $tembusan)
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-white">{{ $index + 1 }}.</span>
                                    <input type="text" name="tembusan[]" class="form-control" value="{{ $tembusan }}" placeholder="Tembusan lainnya...">
                                    <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                                </div>
                                @endforeach
                            @else
                                {{-- Jika kosong tampilkan 1 baris kosong --}}
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-white">1.</span>
                                    <input type="text" name="tembusan[]" class="form-control" placeholder="Contoh: Arsip Internal Audit">
                                    <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-tembusan">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Baris Tembusan
                        </button>
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

<style>
    .ck-editor__editable {
        min-height: 300px;
    }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#isi_surat'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo' ]
        })
        .catch(error => {
            console.error(error);
        });

    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('tembusan-wrapper');
        const btnAdd = document.getElementById('add-tembusan');

        function updateNumbers() {
            const rows = wrapper.querySelectorAll('.input-group');
            rows.forEach((row, index) => {
                row.querySelector('.input-group-text').innerText = (index + 1) + '.';
            });
        }

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