@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar SP2A</h3>
        <a href="{{ route('sp2a.create') }}" class="btn btn-primary">+ Buat SP2A Baru</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nomor SP2A</th>
                        <th>Tanggal</th>
                        <th>Kepada</th>
                        <th>Status Workflow</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sp2as as $sp2a)
                    <tr>
                        <td>
                            @if($sp2a->nomor_sp2a) 
                                <span class="fw-bold text-primary">{{ $sp2a->nomor_sp2a }}</span> 
                            @else 
                                <span class="text-muted fst-italic">- Belum Terbit -</span> 
                            @endif
                        </td>

                        <td>{{ $sp2a->tanggal_surat->format('d M Y') }}</td>
                        
                        <td>
                            <div class="fw-bold">{{ $sp2a->kepada_nama }}</div>
                            <small class="text-muted">{{ $sp2a->kepada_email }}</small>
                        </td>

                        <td>
                            @if($sp2a->current_step == 'finished')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai (Approved)</span>
                            @elseif($sp2a->current_step == 'staff')
                                <span class="badge bg-danger"><i class="bi bi-exclamation-circle"></i> Perlu Perbaikan</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> {{ $sp2a->status }}</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('sp2a.show', $sp2a->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>

                                @if($sp2a->current_step == 'finished')
                                    <a href="#" class="btn btn-sm btn-outline-success" title="Unduh PDF">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                @endif

                                @if($sp2a->current_step == 'staff' && Auth::user()->role == 'staff')
                                    <a href="{{ route('sp2a.edit', $sp2a->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Revisi
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($sp2as->isEmpty())
                <div class="text-center p-4 text-muted">
                    <p>Belum ada dokumen SP2A.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection