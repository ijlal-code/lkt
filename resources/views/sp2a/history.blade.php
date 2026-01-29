@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-success"><i class="bi bi-clock-history me-2"></i>Riwayat Approval</h3>
        </div>
        <a href="{{ route('sp2a.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Nomor</th>
                        <th>Kepada</th>
                        <th>Status Terkini</th>
                        <th>Waktu Approve Anda</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $sp2a)
                    <tr>
                        <td class="px-4 fw-bold text-primary">
                            {{ $sp2a->nomor_sp2a ?? 'Proses' }}
                        </td>
                        <td>{{ $sp2a->kepada_nama }}</td>
                        
                        {{-- STATUS REALTIME --}}
                        <td><span class="badge bg-info text-dark">{{ $sp2a->status }}</span></td>

                        <td>
                            @php
                                $date = null;
                                if(Auth::user()->role == 'sm') $date = $sp2a->approved_sm_at;
                                elseif(Auth::user()->role == 'smqa') $date = $sp2a->approved_smqa_at;
                                elseif(Auth::user()->role == 'gm') $date = $sp2a->approved_gm_at;
                            @endphp
                            {{ $date ? $date->format('d M Y H:i') : '-' }}
                        </td>

                        <td class="text-center">
                            {{-- TOMBOL PDF DI RIWAYAT --}}
                            <a href="{{ route('sp2a.download', $sp2a->id) }}" class="btn btn-sm btn-danger" title="Download PDF">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                            <a href="{{ route('sp2a.show', $sp2a->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">Belum ada riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection