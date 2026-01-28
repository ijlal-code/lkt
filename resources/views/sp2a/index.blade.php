@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar SP2A</h3>
        <a href="{{ route('sp2a.create') }}" class="btn btn-primary">+ Buat SP2A Baru</a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nomor SP2A</th>
                        <th>Tanggal</th>
                        <th>Kepada</th>
                        <th>Email Tujuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sp2as as $sp2a)
                    <tr>
                        <td>
                            @if($sp2a->nomor_sp2a) 
                                <span class="fw-bold">{{ $sp2a->nomor_sp2a }}</span> 
                            @else 
                                <span class="text-muted">Draft</span> 
                            @endif
                        </td>
                        <td>{{ $sp2a->tanggal_surat->format('d M Y') }}</td>
                        <td>{{ $sp2a->kepada_nama }}</td>
                        <td>{{ $sp2a->kepada_email }}</td>
                        <td>
                            @if($sp2a->status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </td>
                        <td class="text-center">
    @if($sp2a->status == 'Draft')
        <form action="{{ route('sp2a.process', $sp2a->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-primary fw-bold" onclick="return confirm('Proses SP2A ini? Email akan dikirim ke semua pihak terkait.')">
                <i class="bi bi-send-check"></i> PROSES
            </button>
        </form>
        @else
        <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
        <a href="#" class="btn btn-sm btn-info text-white">Unduh PDF</a>
    @endif
</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection