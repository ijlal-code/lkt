@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4 align-items-end">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-people-fill text-primary me-2"></i> Manajemen Akun & Role
            </h2>
            <p class="text-muted mb-0">Kelola hak akses pengguna, jabatan, dan kredensial sistem.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm px-4 py-2">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah User Baru
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase">Informasi Pengguna</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase">Email</th>
                            <th class="py-3 text-secondary small fw-bold text-uppercase text-center">Role / Jabatan</th>
                            <th class="text-center pe-4 py-3 text-secondary small fw-bold text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder bg-light text-primary fw-bold rounded-circle me-3">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-nowrap">{{ $user->name }}</div>
                                        <div class="small text-muted">ID: #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark">{{ $user->email }}</span>
                            </td>
                            <td class="text-center">
                                @if($user->role == 'admin') 
                                    <span class="badge-role bg-dark-subtle text-dark border border-dark-subtle">Admin</span>
                                @elseif($user->role == 'auditi') 
                                    <span class="badge-role bg-danger-subtle text-danger border border-danger-subtle">Auditi</span>
                                @elseif($user->role == 'auditor') 
                                    <span class="badge-role bg-info-subtle text-info border border-info-subtle">Auditor</span>
                                @elseif($user->role == 'k3') 
                                    <span class="badge-role bg-warning-subtle text-warning-emphasis border border-warning-subtle">Staff K3</span>
                                @else 
                                    <span class="badge-role bg-secondary-subtle text-secondary border border-secondary-subtle">{{ ucfirst($user->role) }}</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- <a href="{{ route('users.edit', $user->id) }}" class="btn btn-action-edit" title="Edit User">
                                        <i class="bi bi-pencil-square"></i>
                                    </a> --}}

                                    @if($user->id !== Auth::id()) {{-- Mencegah menghapus diri sendiri --}}
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete" title="Hapus User">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small italic">Aktif</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Dasar */
    body { background-color: #f8fafc; }
    
    .custom-table thead { background-color: #fcfcfd; border-bottom: 1px solid #f1f1f1; }
    .custom-table tbody tr { transition: all 0.2s ease; }
    .custom-table tbody tr:hover { background-color: #f8faff; }

    /* Avatar Inisial */
    .avatar-placeholder {
        width: 40px; height: 40px; display: flex;
        align-items: center; justify-content: center; font-size: 0.9rem;
        border: 1px solid #e2e8f0;
    }

    /* Badge Role Styling */
    .badge-role {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        min-width: 80px;
    }

    /* Action Buttons */
    .btn-action-delete {
        background-color: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
        width: 34px; height: 34px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .btn-action-delete:hover {
        background-color: #e11d48;
        color: white;
    }

    .btn-action-edit {
        background-color: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
        width: 34px; height: 34px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .btn-action-edit:hover {
        background-color: #2563eb;
        color: white;
    }
</style>
@endsection