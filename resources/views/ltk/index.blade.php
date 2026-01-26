@extends('layouts.app')

@section('content')
<div class="card shadow-sm mt-4">
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Laporan Temuan Ketidaksesuaian (LTK)</h5>
        <a href="{{ route('ltk.create') }}" class="btn btn-light btn-sm fw-bold">
            <i class="bi bi-plus-circle"></i> Buat LTK Baru
        </a>
    </div>
    <div class="card-body">
        
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th class="text-center" width="5%">No</th>
                    <th>No LTK</th>
                    <th>Tanggal Audit</th>
                    <th>Kepada (Unit Kerja)</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ltks as $key => $d)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="fw-bold">{{ $d->nomor_lkt }}</td> <td>{{ $d->tanggal->format('d M Y') }}</td> <td>
                        <div class="fw-bold">{{ $d->kepada }}</div>
                        <small class="text-muted">{{ $d->unit_kerja }}</small>
                    </td>
                    <td class="text-center">
                        @if($d->status == 'Selesai')
                            <span class="badge bg-success">Selesai (Closed)</span>
                        @else
                            <span class="badge bg-warning text-dark">Lanjut (Open)</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- PERBAIKAN UTAMA DI SINI: Gunakan $d->id --}}
                        <a href="{{ route('ltk.pdf', $d->id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> Download PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Belum ada data LKT. Silakan buat baru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection