<!DOCTYPE html>
<html>
<head>
    <title>Daftar LTK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="card shadow">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Daftar Laporan Temuan Ketidaksesuaian (LTK)</h4>
            <a href="{{ route('ltk.create') }}" class="btn btn-light btn-sm">+ Buat LTK Baru</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No LTK</th>
                        <th>Tanggal Audit</th>
                        <th>Kepada (Auditee)</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ltks as $d)
                    <tr>
                        <td>{{ $d->LTK_No }}</td>
                        <td>{{ $d->Tanggal_Audit }}</td>
                        <td>{{ $d->Kepada }}</td>
                        <td>
                            <span class="badge {{ $d->Status_Temuan == 'Closed' ? 'bg-success' : 'bg-warning' }}">
                                {{ $d->Status_Temuan ?? 'Open' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('ltk.pdf', $d->ID_LTK) }}" class="btn btn-primary btn-sm">Download PDF</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>