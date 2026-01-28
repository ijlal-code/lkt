@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Kotak Masuk (SP2A)</h3>
    
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            @if($pesan->isEmpty())
                <p class="text-center text-muted">Tidak ada pesan baru.</p>
            @else
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pengirim (Admin)</th>
                            <th>No. Surat</th>
                            <th>Perihal</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesan as $p)
                        <tr>
                            <td>{{ $p->dari_nama }}</td>
                            <td>{{ $p->nomor_sp2a }}</td>
                            <td>{{ $p->perihal }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_surat)->format('d M Y') }}</td>
                            <td>
                                @if($p->status == 'Approved By System')
                                    <span class="badge bg-success">Approved by System</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu Approval</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pesan.show', $p->id) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-file-earmark-pdf"></i> Lihat PDF
                                </a>

                                @if(Auth::user()->role == 'k3' && $p->status != 'Approved By System')
                                    <form action="{{ route('pesan.approve', $p->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-bold" onclick="return confirm('Apakah Anda yakin menyetujui dokumen ini? Sistem akan membubuhkan tanda tangan digital.')">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </button>
                                    </form>
                                @endif
                                
                                @if($p->status == 'Approved By System')
                                    <a href="{{ route('pesan.show', $p->id) }}" download class="btn btn-sm btn-secondary">
                                        <i class="bi bi-download"></i> Unduh
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection