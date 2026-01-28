<!DOCTYPE html>
<html>
<head>
    <title>Login - Sistem Audit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Login Sistem</h3>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('register') }}">Belum punya akun? Daftar</a>
        </div>
    </div>

</body>
</html>