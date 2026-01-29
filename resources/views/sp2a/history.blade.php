@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-success"><i class="bi bi-clock-history me-2"></i>Riwayat Approval Saya</h3>
            <p class="text-muted mb-0">Daftar dokumen SP2A yang telah Anda setujui.</p>
        </div>
        <a href="{{ route('sp2a.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Tugas
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">Nomor / Tanggal</th>
                            <th class="py-3">Kepada</th>
                            <th class="py-3">Status Terkini</th>
                            <th class="py-3">Waktu Anda Approve</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $sp2a)
                        <tr>
                            {{-- NOMOR --}}
                            <td class="px-4">
                                @if($sp2a->nomor_sp2a) 
                                    <div class="fw-bold text-primary">{{ $sp2a->nomor_sp2a }}</div>
                                @else 
                                    <span class="text-muted fst-italic small">Proses</span>
                                @endif
                                <div class="text-muted small">
                                    {{ $sp2a->tanggal_surat->format('d M Y') }}
                                </div>
                            </td>
                            
                            {{-- KEPADA --}}
                            <td>
                                <span class="fw-bold text-dark">{{ $sp2a->kepada_nama }}</span>
                            </td>

                            {{-- STATUS TERKINI --}}
                            <td>
                                @if($sp2a->current_step == 'finished')
                                    <span class="badge bg-success rounded-pill">Selesai (Final)</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">{{ $sp2a->status }}</span>
                                @endif
                            </td>

                            {{-- WAKTU APPROVE (Sesuai Role) --}}
                            <td>
                                @php
                                    $tglApprove = null;
                                    if(Auth::user()->role == 'sm') $tglApprove = $sp2a->approved_sm_at;
                                    if(Auth::user()->role == 'smqa') $tglApprove = $sp2a->approved_smqa_at;
                                    if(Auth::user()->role == 'gm') $tglApprove = $sp2a->approved_gm_at;
                                @endphp

                                @if($tglApprove)
                                    <div class="text-success fw-bold">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </div>
                                    <small class="text-muted">{{ $tglApprove->format('d M Y H:i') }}</small>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <a href="{{ route('sp2a.show', $sp2a->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Lihat Dokumen
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Belum ada riwayat approval.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection