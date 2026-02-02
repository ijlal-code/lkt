@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Pengaturan Sistem</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nama General Manager (GM) Internal Audit</label>
                            <input type="text" name="gm_name" class="form-control form-control-lg" value="{{ $gm->value }}" placeholder="Contoh: Ir. Budi Santoso, M.M." required>
                            <div class="form-text text-muted">
                                Nama ini akan otomatis muncul sebagai penanda tangan pada setiap Surat Peringatan (SP2A) yang dibuat baru.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection