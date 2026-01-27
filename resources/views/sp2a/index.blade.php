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
                    @foreach($sp2as as $s)
                    <tr>
                        <td>
                            @if($s->nomor_sp2a) 
                                <span class="fw-bold">{{ $s->nomor_sp2a }}</span> 
                            @else 
                                <span class="text-muted">Draft</span> 
                            @endif
                        </td>
                        <td>{{ $s->tanggal_surat->format('d M Y') }}</td>
                        <td>{{ $s->kepada_nama }}</td>
                        <td>{{ $s->kepada_email }}</td>
                        <td>
                            @if($s->status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </td>
                        <td>
                            @if($s->status == 'Draft')
                                <form action="{{ route('sp2a.approve', $s->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success" onclick="return confirm('Approve dan Kirim Email ke {{ $s->kepada_email }}?')">
                                        Approve & Kirim
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>Terkirim</button>
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