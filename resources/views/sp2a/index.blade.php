@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Surat Peringatan 2A (SP2A)</h2>
        <a href="{{ route('ltk.index') }}" class="btn btn-primary">
            + Buat SP2A dari LKT
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($sp2as->isEmpty())
                <div class="text-center py-4">
                    <p class="text-muted">Belum ada data SP2A.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nomor SP2A</th>
                                <th>Referensi LKT</th>
                                <th>Kepada (Auditee)</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sp2as as $index => $sp2a)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($sp2a->nomor_sp2a)
                                        <span class="fw-bold text-dark">{{ $sp2a->nomor_sp2a }}</span>
                                    @else
                                        <span class="text-muted fst-italic">- Belum ada nomor -</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="text-decoration-none">
                                        {{ $sp2a->ltk->nomor_lkt ?? '-' }}
                                    </a>
                                </td>
                                <td>{{ $sp2a->kepada }}</td>
                                <td>{{ $sp2a->tanggal_sp2a->format('d M Y') }}</td>
                                <td>
                                    @if($sp2a->status == 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Draft</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($sp2a->status == 'Draft')
                                        <form action="{{ route('sp2a.approve', $sp2a->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin approve surat ini?')">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Approved</button>
                                        <a href="#" class="btn btn-sm btn-info text-white">Cetak PDF</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection