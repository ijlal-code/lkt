<!DOCTYPE html>
<html>
<head>
    <title>Register - Sistem Audit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4" style="width: 450px;">
        <h3 class="text-center mb-4">Daftar Akun Baru</h3>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="mb-3">
                <label>Daftar Sebagai:</label>
                <select name="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="auditi">Auditi (User)</option>
                    <option value="k3">Staff K3</option>
                    <option value="auditor">Auditor</option>
                    <option value="staff">Staff Unit</option>
                    <option value="atasan_staff">Atasan Staff</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}">Sudah punya akun? Login</a>
        </div>
    </div>

</body>
</html>