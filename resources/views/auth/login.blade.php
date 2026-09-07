<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ResiCycle</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --rc-primary: #2D6A4F; --rc-primary-light: #40916C; --rc-primary-lighter: #52B788;
            --rc-accent: #1B4332; --rc-danger: #E76F51; --rc-text-muted: #6C757D;
        }
        * { font-family: 'Inter', sans-serif; }
        h1, h2, .heading { font-family: 'Outfit', sans-serif; }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--rc-accent) 0%, var(--rc-primary) 50%, var(--rc-primary-light) 100%);
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }
        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 70%, rgba(82,183,136,0.15) 0%, transparent 50%),
                        radial-gradient(circle at 70% 30%, rgba(255,255,255,0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-20px, 20px) rotate(5deg); }
        }
        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }
        .auth-logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            color: var(--rc-primary);
            text-align: center;
        }
        .auth-subtitle {
            text-align: center;
            color: var(--rc-text-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .form-control-rc {
            border: 2px solid rgba(45,106,79,0.15);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .form-control-rc:focus {
            border-color: var(--rc-primary);
            box-shadow: 0 0 0 3px rgba(45,106,79,0.12);
        }
        .form-label-rc {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--rc-accent);
        }
        .btn-rc {
            background: linear-gradient(135deg, var(--rc-primary) 0%, var(--rc-primary-light) 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.7rem;
            width: 100%;
            font-size: 0.95rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(45,106,79,0.3);
        }
        .btn-rc:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(45,106,79,0.4); color: #fff; }
        .flash-message {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.88rem;
        }
        .flash-error { background: rgba(231,111,81,0.1); color: #C0392B; border-left: 4px solid var(--rc-danger); }
        .flash-success { background: rgba(45,106,79,0.08); color: var(--rc-primary); border-left: 4px solid var(--rc-primary-lighter); }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card" id="auth-card">
            <div class="auth-logo"><i class="bi bi-recycle me-1"></i> ResiCycle</div>
            <p class="auth-subtitle">Masuk ke akun Anda</p>

            @if(session('error'))
                <div class="flash-message flash-error">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="flash-message flash-success">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="/login" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label form-label-rc">Email</label>
                    <input type="email" name="email" id="email" class="form-control form-control-rc @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@contoh.com" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label form-label-rc">Password</label>
                    <input type="password" name="password" id="password" class="form-control form-control-rc @error('password') is-invalid @enderror"
                           placeholder="••••••••" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-rc" id="btnLogin">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </button>
            </form>

            <p class="text-center mt-3 mb-0" style="font-size: 0.88rem; color: var(--rc-text-muted);">
                Belum punya akun?
                <a href="/register" style="color: var(--rc-primary); font-weight: 600; text-decoration: none;">Daftar di sini</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            gsap.from('#auth-card', { opacity: 0, y: 40, duration: 0.6, ease: 'back.out(1.7)' });

            document.getElementById('loginForm').addEventListener('submit', function() {
                const btn = document.getElementById('btnLogin');
                gsap.to(btn, { scale: 0.95, duration: 0.1, yoyo: true, repeat: 1 });
            });
        });
    </script>
</body>
</html>
