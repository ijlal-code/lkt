@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white">Buat SP2A Baru (Mandiri)</div>
        <div class="card-body">
            <form action="{{ route('sp2a.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label>Perihal</label>
                        <input type="text" name="perihal" class="form-control" value="Surat Peringatan 2A">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Kepada (Auditee)</label>
                        <select name="kepada_id" class="form-select" required>
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($contacts as $c)
                                <option value="{{ $c->id }}">{{ $c->nama }} ({{ $c->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Tembusan/CC (Auditor)</label>
                        <select name="cc_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($contacts as $c)
                                <option value="{{ $c->id }}">{{ $c->nama }} ({{ $c->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Dasar Surat (Penyebab)</label>
                    <textarea name="dasar_surat" class="form-control" rows="3" placeholder="Contoh: Berdasarkan hasil audit tanggal sekian ditemukan ketidaksesuaian..." required></textarea>
                </div>

                <div class="mb-3">
                    <label>Isi Surat / Peringatan</label>
                    <textarea name="isi_surat" class="form-control" rows="5" required>Maka dengan ini kami menerbitkan Surat Peringatan 2A agar segera ditindaklanjuti...</textarea>
                </div>

                <button type="submit" class="btn btn-danger">Simpan Draft</button>
                <a href="{{ route('sp2a.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection