<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — {{ config('app.name', 'Service HP') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #1e3a5f 100%);
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0,0,0,.3);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
            padding: 2.5rem 2rem 2rem;
            text-align: center;
        }
        .login-header .icon-circle {
            width: 72px; height: 72px;
            background: rgba(255,255,255,.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .login-body { padding: 2rem; background: #fff; }
        .form-control { border-radius: .5rem; padding: .65rem .85rem; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 .2rem rgba(59,130,246,.25); }
        .btn-login {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            border-radius: .5rem;
            padding: .7rem;
            font-weight: 600;
            transition: transform .15s ease;
        }
        .btn-login:hover { transform: translateY(-1px); background: linear-gradient(135deg, #2563eb, #1d4ed8); }
        .input-group-text { border-radius: .5rem 0 0 .5rem; background: #f8fafc; }
        .input-group .form-control { border-radius: 0 .5rem .5rem 0; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="icon-circle"><i class="bi bi-phone"></i></div>
            <h4 class="mb-1 fw-bold">{{ config('app.name', 'Service HP') }}</h4>
            <p class="mb-0 opacity-75 small">Sistem Manajemen Service Handphone</p>
        </div>
        <div class="login-body">
            @if(session('status'))
                <div class="alert alert-success small">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger small">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="nama@email.com" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" placeholder="••••••••" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // #region agent log
        fetch('http://127.0.0.1:7278/ingest/b148228f-8cb8-4dac-a143-a6f068fa4d3c',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'df97ec'},body:JSON.stringify({sessionId:'df97ec',runId:'pre-fix',hypothesisId:'F',location:'login.blade.php:DOMContentLoaded',message:'Login page rendered in browser',data:{formAction:document.querySelector('form')?.getAttribute('action')||null,hasCsrf:!!document.querySelector('input[name=_token]')},timestamp:Date.now()})}).catch(()=>{});
        // #endregion
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
        (() => {
            'use strict';
            document.querySelectorAll('.needs-validation').forEach(form => {
                form.addEventListener('submit', e => {
                    if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                    form.classList.add('was-validated');
                });
            });
        })();
    </script>
</body>
</html>
