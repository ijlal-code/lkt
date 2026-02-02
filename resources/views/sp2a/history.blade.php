@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-success"><i class="bi bi-clock-history me-2"></i>Riwayat Approval</h3>
            <p class="text-muted small mb-0">Arsip dokumen SP2A yang telah disetujui</p>
        </div>
         <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
    </div>

    {{-- CARD FILTER SEARCH (SATU KOLOM) --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body bg-light rounded-4">
            <form action="{{ route('sp2a.history') }}" method="GET">
                <div class="row g-2 align-items-center">
                    
                    {{-- Label --}}
                    <div class="col-md-2">
                        <span class="fw-bold text-secondary"><i class="bi bi-search me-1"></i> Pencarian Arsip:</span>
                    </div>

                    {{-- Input Search (Flexible) --}}
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-calendar-event"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Ketik Tahun (2024), Bulan (Oktober), Tanggal (25), atau Nomor Surat..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-primary shadow-sm fw-bold w-100">
                            Cari
                        </button>
                    </div>

                </div>
                
                {{-- Tombol Reset (Muncul jika sedang mencari) --}}
                @if(request('search'))
                    <div class="mt-2 text-end">
                        <a href="{{ route('sp2a.history') }}" class="text-decoration-none text-danger small fw-bold">
                            <i class="bi bi-x-circle-fill"></i> Hapus Filter / Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Nomor / Tanggal Surat</th>
                        <th class="py-3">Kepada</th>
                        <th class="py-3">Status Terkini</th>
                        <th class="py-3">Waktu Approve Anda</th>
                        <th class="text-center py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $sp2a)
                    <tr>
                        <td class="px-4">
                            <div class="fw-bold text-primary font-monospace">{{ $sp2a->nomor_sp2a ?? 'Proses' }}</div>
                            {{-- Format tanggal Indonesia untuk memudahkan pembacaan --}}
                            <small class="text-muted">{{ $sp2a->tanggal_surat->translatedFormat('d F Y') }}</small>
                        </td>
                        <td>
    {{-- Cek apakah array, jika ya gabung dengan koma. Jika string tampilkan langsung --}}
    {{ is_array($sp2a->kepada_nama) ? implode(', ', $sp2a->kepada_nama) : $sp2a->kepada_nama }}
</td>
                        
                        {{-- STATUS REALTIME --}}
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill">
                                {{ $sp2a->status }}
                            </span>
                        </td>

                        <td>
                            @php
                                $date = null;
                                if(Auth::user()->role == 'sm') $date = $sp2a->approved_sm_at;
                                elseif(Auth::user()->role == 'smqa') $date = $sp2a->approved_smqa_at;
                                elseif(Auth::user()->role == 'gm') $date = $sp2a->approved_gm_at;
                            @endphp
                            
                            @if($date)
                                <div class="text-dark fw-bold"><i class="bi bi-check-circle-fill text-success me-1"></i> {{ $date->format('H:i') }}</div>
                                <div class="small text-muted">{{ $date->format('d M Y') }}</div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="btn-group shadow-sm rounded-3" role="group">
                                <a href="{{ route('sp2a.show', $sp2a->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('sp2a.download', $sp2a->id) }}" class="btn btn-sm btn-danger" title="Download PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
                            Tidak ada dokumen yang cocok dengan pencarian <strong>"{{ request('search') }}"</strong>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection