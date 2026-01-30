@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-success"><i class="bi bi-clock-history me-2"></i>Riwayat Approval</h3>
            <p class="text-muted small mb-0">Arsip dokumen SP2A yang telah disetujui</p>
        </div>
        <a href="{{ route('sp2a.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- CARD FILTER --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body bg-light rounded-4">
            <form action="{{ route('sp2a.history') }}" method="GET" class="row g-2 align-items-center">
                
                {{-- Label --}}
                <div class="col-auto">
                    <span class="fw-bold text-secondary"><i class="bi bi-funnel me-1"></i> Filter:</span>
                </div>

                {{-- Dropdown Bulan --}}
                <div class="col-auto">
                    <select name="bulan" class="form-select border-0 shadow-sm" style="min-width: 150px;">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }} </option>
                        @endforeach
                    </select>
                </div>

                {{-- Dropdown Tahun --}}
                <div class="col-auto">
                    <select name="tahun" class="form-select border-0 shadow-sm" style="min-width: 120px;">
                        <option value="">-- Tahun --</option>
                        @php $currentYear = date('Y'); @endphp
                        @foreach(range($currentYear, $currentYear - 3) as $y)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Cari --}}
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary shadow-sm fw-bold">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    @if(request('bulan') || request('tahun'))
                        <a href="{{ route('sp2a.history') }}" class="btn btn-outline-danger shadow-sm ms-1" title="Reset Filter">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

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
                            <small class="text-muted">{{ $sp2a->tanggal_surat->format('d M Y') }}</small>
                        </td>
                        <td>{{ $sp2a->kepada_nama }}</td>
                        
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
                            {{-- TOMBOL PDF DI RIWAYAT --}}
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
                            <i class="bi bi-calendar-x fs-1 d-block mb-2 text-secondary"></i>
                            Tidak ada riwayat ditemukan untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection