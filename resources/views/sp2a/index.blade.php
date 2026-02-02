@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i> Daftar SP2A</h2>
            <p class="text-muted mb-0">Kelola dokumen Surat Peringatan Tahap 2A</p>
        </div>
        @if(in_array(Auth::user()->role, ['admin', 'staff']))
        <a href="{{ route('sp2a.create') }}" class="btn btn-primary shadow-sm fw-bold">
            <i class="bi bi-plus-lg me-1"></i> Buat SP2A Baru
        </a>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- Tambahkan style min-height agar dropdown tidak terpotong jika data sedikit --}}
                <table class="table table-hover align-middle mb-0" style="min-height: 200px;">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nomor Surat</th>
                            <th class="py-3">Kepada</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sp2as as $s)
                        <tr>
                            <td class="ps-4 fw-bold font-monospace text-primary">
                                {{ $s->nomor_sp2a ?? 'DRAFT' }}
                            </td>
                            <td>
                               <div class="fw-bold">
    @if(is_array($s->kepada_nama))
        @foreach($s->kepada_nama as $nama)
            <div>{{ $nama }}</div>
        @endforeach
    @else
        {{ $s->kepada_nama }}
    @endif
</div>
                                <div class="small text-muted">Dari: {{ $s->dari_nama }}</div>
                            </td>
                            <td>{{ $s->tanggal_surat->format('d/m/Y') }}</td>
                            <td class="text-center">
                                @php
                                    $badge = 'bg-secondary';
                                    if($s->current_step == 'staff') $badge = 'bg-secondary'; // Draft
                                    elseif(str_contains($s->status, 'Menunggu')) $badge = 'bg-warning text-dark';
                                    elseif(str_contains($s->status, 'Perbaikan')) $badge = 'bg-danger';
                                    elseif($s->current_step == 'finished') $badge = 'bg-success';
                                    elseif(str_contains($s->status, 'Disetujui')) $badge = 'bg-info text-dark';
                                @endphp
                                <span class="badge {{ $badge }} rounded-pill px-3 py-2">
                                    {{ $s->status }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                {{-- DROPDOWN MENU START --}}
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm shadow-sm border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i> Pilihan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                        
                                        {{-- 1. MENU LIHAT DETAIL (Semua User) --}}
                                        <li>
                                            <a class="dropdown-item py-2" href="{{ route('sp2a.show', $s->id) }}">
                                                <i class="bi bi-eye text-primary me-2"></i> Lihat Detail
                                            </a>
                                        </li>

                                        {{-- 2. MENU STAFF (Edit & Proses - Khusus Draft/Revisi) --}}
                                        @if($s->current_step == 'staff' && Auth::user()->role == 'staff')
                                            <li><hr class="dropdown-divider"></li>
                                            
                                            {{-- Edit --}}
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('sp2a.edit', $s->id) }}">
                                                    <i class="bi bi-pencil text-warning me-2"></i> Edit
                                                </a>
                                            </li>

                                            {{-- Proses (Trigger JS) --}}
                                            <li>
                                                <button type="button" class="dropdown-item py-2 fw-bold text-success" onclick="confirmProcess('{{ $s->id }}')">
                                                    <i class="bi bi-send-fill me-2"></i> Proses ke SM
                                                </button>
                                            </li>
                                        @endif

                                        {{-- 3. MENU APPROVER (Approve) --}}
                                        @php
                                            $showApprove = false;
                                            $r = Auth::user()->role;
                                            if(($r == 'sm' && $s->current_step == 'sm') || 
                                               ($r == 'smqa' && $s->current_step == 'smqa') || 
                                               ($r == 'gm' && $s->current_step == 'gm')) {
                                                $showApprove = true;
                                            }
                                        @endphp

                                        @if($showApprove)
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item py-2 fw-bold text-success" onclick="confirmApprove('{{ $s->id }}')">
                                                    <i class="bi bi-check-circle-fill me-2"></i> Approve
                                                </button>
                                            </li>
                                        @endif

                                        {{-- 4. MENU HAPUS (Admin & Staff) --}}
                                        @if(in_array(Auth::user()->role, ['admin', 'staff']))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item py-2 text-danger" onclick="confirmDelete('{{ $s->id }}')">
                                                    <i class="bi bi-trash me-2"></i> Hapus
                                                </button>
                                            </li>
                                        @endif

                                    </ul>
                                </div>
                                {{-- DROPDOWN END --}}

                                {{-- HIDDEN FORMS (Diperlukan untuk JS SweetAlert) --}}
                                
                                {{-- Form Proses --}}
                                <form id="process-form-{{ $s->id }}" action="{{ route('sp2a.process', $s->id) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                                {{-- Form Hapus --}}
                                <form id="delete-form-{{ $s->id }}" action="{{ route('sp2a.destroy', $s->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                {{-- Form Approve --}}
                                <form id="approve-form-{{ $s->id }}" action="{{ route('sp2a.approve', $s->id) }}" method="POST" style="display: none;">
                                    @csrf
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada data SP2A
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmProcess(id) {
        Swal.fire({
            title: 'Kirim Dokumen?',
            text: "Dokumen Draft ini akan dikirim ke Senior Manager (SM) untuk diperiksa.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({title: 'Mengirim...', didOpen: () => Swal.showLoading()});
                document.getElementById('process-form-' + id).submit();
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Dokumen?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function confirmApprove(id) {
        Swal.fire({
            title: 'Setujui Dokumen?',
            text: "Dokumen akan diteruskan ke tahap selanjutnya.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-form-' + id).submit();
            }
        });
    }

    // Flash Message Handler
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
    @endif
</script>
@endsection