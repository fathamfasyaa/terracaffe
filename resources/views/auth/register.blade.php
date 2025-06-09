<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            font-family: 'Poppins', sans-serif;
        }

        .register-card {
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }

        .btn-primary {
            background-color: #764ba2;
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #5a3b8c;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(118, 75, 162, 0.5);
            box-shadow: 0 0 10px rgba(118, 75, 162, 0.2);
            transition: 0.3s ease-in-out;
        }

        .form-control:focus {
            box-shadow: 0 0 15px rgba(118, 75, 162, 0.7);
            border-color: #764ba2;
            outline: none;
        }

        .register-title {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 2rem;
            text-shadow: 2px 2px 15px rgba(118, 75, 162, 0.7);
            letter-spacing: 2px;
            animation: text-glow 1.5s infinite alternate;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes text-glow {
            from {
                text-shadow: 2px 2px 10px rgba(118, 75, 162, 0.5);
            }
            to {
                text-shadow: 2px 2px 20px rgba(118, 75, 162, 1);
            }
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="register-card">
            <h2 class="text-center mb-3 register-title">🚀 Register</h2>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama..." required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email..." required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Buat password..." required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person-badge text-muted"></i></span>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="admin">Admin</option>
                            {{-- <option value="kasir" selected>Kasir</option>
                            <option value="pemilik">Pemilik</option> --}}
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Register</button>
            </form>

            <p class="text-center mt-3">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #764ba2;">Login</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
