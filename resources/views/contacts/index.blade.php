@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Manajemen Kontak & Email</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Tambah Kontak Baru</h5>
                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label>Nama Lengkap / Unit</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Email Tujuan</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jabatan (Opsional)</label>
                        <input type="text" name="jabatan" class="form-control">
                    </div>
                    <button class="btn btn-primary w-100">Simpan Kontak</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $c)
                        <tr>
                            <td>{{ $c->nama }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->jabatan }}</td>
                            <td>
                                <form action="{{ route('contacts.destroy', $c->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">X</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection