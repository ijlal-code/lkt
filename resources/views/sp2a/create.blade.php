@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white p-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-file-earmark-plus me-2"></i>Buat SP2A Baru</h4>
        </div>
        <div class="card-body p-4">

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
                        <input type="text" name="dari_nama" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>

                    <hr class="my-4">

                    {{-- TUJUAN (DINAMIS LEBIH DARI SATU) --}}
                    <div class="col-12">
                        <label class="form-label fw-bold text-danger">Kepada Yth (Daftar Penerima)</label>
                        <div class="form-text mb-2">Masukkan nama-nama penerima surat (Auditi/Unit).</div>
                        
                        <div id="kepada-wrapper">
    @if(old('kepada_nama'))
        @foreach(old('kepada_nama') as $index => $val)
            <div class="input-group mb-2">
                <span class="input-group-text bg-white">{{ $loop->iteration }}.</span>
                <input type="text" name="kepada_nama[]" class="form-control" value="{{ $val }}" required>
                @if($index > 0)
                    <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                @endif
            </div>
        @endforeach
    @else
        <div class="input-group mb-2">
            <span class="input-group-text bg-white">1.</span>
            <input type="text" name="kepada_nama[]" class="form-control" placeholder="Nama Penerima 1" required>
        </div>
    @endif
</div>
                        
                        <button type="button" class="btn btn-sm btn-outline-danger mt-1" id="add-kepada">
                            <i class="bi bi-person-plus-fill me-1"></i> Tambah Penerima
                        </button>
                    </div>

                    {{-- DETAIL SURAT --}}
                    <div class="col-md-12 mt-3">
                        <label class="form-label fw-bold">Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="Surat Peringatan 2A" required>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-bold">Dasar Surat / Temuan</label>
                        <input type="text" name="dasar_surat" class="form-control" placeholder="Contoh: Berdasarkan LKT No. 001/AUDIT/2026..." required>
                    </div>

                    {{-- ISI SURAT --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">Isi Surat Lengkap</label>
                        <textarea name="isi_surat" id="isi_surat" class="form-control">
<p>Dengan hormat,</p>
<p>Sehubungan dengan temuan ketidaksesuaian yang belum ditindaklanjuti...</p>
                        </textarea>
                    </div>

                    {{-- PENANDA TANGAN (OTOMATIS DARI SETTING) --}}
                    <div class="col-md-12 mt-4">
                         <div class="p-3 bg-light border rounded">
                            <label class="form-label fw-bold text-primary">Penanda Tangan (GM Internal Audit)</label>
                            
                            {{-- Input Readonly, user tidak bisa ubah disini --}}
                            <input type="text" name="penanda_tangan_nama" class="form-control border-primary fw-bold" 
                                   value="{{ $gmName }}" readonly>
                            
                            <div class="form-text">
                                Nama ini diambil otomatis dari <a href="{{ route('settings.index') }}">Halaman Pengaturan</a>.
                            </div>
                        </div>
                    </div>

                    {{-- TEMBUSAN (DYNAMIC LIST) --}}
                    <div class="col-12 mt-4">
                        <label class="form-label fw-bold">Tembusan (CC)</label>
                        <div id="tembusan-wrapper">
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white">1.</span>
                                <input type="text" name="tembusan[]" class="form-control" placeholder="Contoh: Arsip Internal Audit">
                                <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-tembusan">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Tembusan
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

<style>
    .ck-editor__editable { min-height: 300px; }
</style>

{{-- Script TinyMCE (Versi 6 Stabil) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

<script>
    tinymce.init({
        selector: '#isi_surat', // ID textarea Anda
        height: 500,
        menubar: false,
        branding: false, // Menghilangkan tulisan "Powered by TinyMCE"
        statusbar: false,
        
        // Plugin Penting untuk Tabel dan Paste dari Word
        plugins: 'table lists advlist autolink link image charmap preview anchor pagebreak',
        
        // Toolbar Lengkap
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | table tabledelete | ' +
                 'forecolor backcolor',

        // KONFIGURASI AGAR FORMAT WORD (WARNA GARIS TABEL) TIDAK HILANG
        paste_data_images: true, // Izinkan gambar di-paste
        paste_as_text: false,    // Pastikan ini FALSE agar format HTML terbawa
        
        // Opsi Tabel agar border styling valid
        table_default_attributes: {
            border: '1'
        },
        table_default_styles: {
            'border-collapse': 'collapse',
            'width': '100%'
        },
        
        // Mengizinkan style inline (seperti border-color) dari Word
        valid_elements: '*[*]', // PENTING: Mengizinkan semua tag dan atribut
        extended_valid_elements: 'table[style|border|width|cellspacing|cellpadding],tr[style],td[style|width|colspan|rowspan],th[style|width|colspan|rowspan]',
        
        // CSS Editor agar terlihat mirip kertas
        content_style: `
            body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.5; padding: 15px; }
            table { border-collapse: collapse; width: 100%; }
            table td, table th { padding: 5px; }
        `
    });
</script>
<script>
   
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- LOGIKA DINAMIS TEMBUSAN ---
        const wrapperTembusan = document.getElementById('tembusan-wrapper');
        document.getElementById('add-tembusan').addEventListener('click', function() {
            addRow(wrapperTembusan, 'tembusan[]', 'Tembusan lainnya...');
        });
        wrapperTembusan.addEventListener('click', function(e) { removeRow(e, wrapperTembusan); });

        // --- LOGIKA DINAMIS KEPADA (PENERIMA) ---
        const wrapperKepada = document.getElementById('kepada-wrapper');
        document.getElementById('add-kepada').addEventListener('click', function() {
            addRow(wrapperKepada, 'kepada_nama[]', 'Nama Penerima lainnya...');
        });
        wrapperKepada.addEventListener('click', function(e) { removeRow(e, wrapperKepada); });

        // Fungsi Tambah Baris
        function addRow(wrapper, inputName, placeholder) {
            const count = wrapper.children.length + 1;
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <span class="input-group-text bg-white">${count}.</span>
                <input type="text" name="${inputName}" class="form-control" placeholder="${placeholder}">
                <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-trash"></i></button>
            `;
            wrapper.appendChild(div);
        }

        // Fungsi Hapus Baris & Update Nomor
        function removeRow(e, wrapper) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.input-group');
                // Sisakan minimal 1 baris
                if (wrapper.children.length > 1) {
                    row.remove();
                    // Update penomoran
                    Array.from(wrapper.children).forEach((child, index) => {
                        child.querySelector('.input-group-text').innerText = (index + 1) + '.';
                    });
                } else {
                    row.querySelector('input').value = '';
                }
            }
        }
    });
</script>
@endsection