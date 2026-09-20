<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Security Verification - Smart Supply Chain System</title>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root, [data-theme="light"] {
            --bg-primary: #F7FAF9;
            --bg-card: #FFFFFF;
            --text-primary: #24322D;
            --text-secondary: #5A6B64;
            --border-color: #E2E8E5;
            --input-bg: #FFFFFF;
            --input-border: #C9D2CD;
            --input-text: #24322D;
            --input-addon-bg: #F0F4F2;
            --primary: #0F7A68;
            --primary-hover: #0D6355;
            --accent-amber: #E8940F;
        }

        [data-theme="dark"] {
            --bg-primary: #0E1512;
            --bg-card: #16211D;
            --text-primary: #F0F4F2;
            --text-secondary: #A3AFA9;
            --border-color: #24322D;
            --input-bg: #0E1512;
            --input-border: #3A4A44;
            --input-text: #F0F4F2;
            --input-addon-bg: #24322D;
            --primary: #3FB39B;
            --primary-hover: #17957F;
            --accent-amber: #F5AC3D;
        }

        body, button, input, select, textarea, h1, h2, h3, h4, h5, h6, label, p, span, div {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif !important;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s ease, color 0.2s ease;
            position: relative;
            padding: 1.5rem 1rem;
        }

        .login-split-container {
            width: 100%;
            max-width: 1060px;
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(14,21,18,.10), 0 4px 6px -4px rgba(14,21,18,.10);
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .login-hero-side {
            background-color: #063831;
            color: #FFFFFF;
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-hero-side::before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: radial-gradient(circle at 80% 20%, rgba(232, 148, 15, 0.12) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-form-side {
            padding: 3.5rem 3rem;
            background-color: var(--bg-card);
        }

        .form-control {
            background-color: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--input-text) !important;
            border-radius: 8px !important;
            padding: 0.75rem 1rem !important;
            font-size: 1.25rem !important;
            letter-spacing: 6px !important;
            text-align: center !important;
            font-weight: 700 !important;
        }

        .form-control:focus {
            background-color: var(--input-bg) !important;
            color: var(--input-text) !important;
            border-color: #17957F !important;
            box-shadow: 0 0 0 2px rgba(15,122,104,.30) !important;
        }

        .btn-primary {
            background: #0F7A68 !important;
            border: 1px solid #0F7A68 !important;
            color: #FFFFFF !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            transition: all 0.15s ease-in-out !important;
        }

        .btn-primary:hover {
            background: #0D6355 !important;
            border-color: #0D6355 !important;
        }

        .text-theme-primary { color: var(--text-primary) !important; }
        .text-theme-muted { color: var(--text-secondary) !important; }

        .theme-toggle-fixed {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="theme-toggle-fixed">
        <button id="themeToggleBtn" class="btn btn-outline-secondary btn-sm px-3 rounded-pill shadow-sm" style="background: var(--bg-card); border-color: var(--border-color); color: var(--text-primary);">
            <i id="themeToggleIcon" class="fa-solid fa-moon me-1"></i>
            <span id="themeToggleText">Dark Mode</span>
        </button>
    </div>

    <div class="login-split-container">
        <div class="row g-0">
            <!-- LEFT HERO COLUMN -->
            <div class="col-lg-6 d-none d-lg-flex login-hero-side">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="mark" style="width:30px; height:30px; border-radius:8px; background:var(--accent-amber); color:#04231F; display:grid; place-items:center; flex-shrink:0;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/>
                                <path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/>
                                <path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/>
                            </svg>
                        </span>
                        <h4 class="fw-bold mb-0 text-white" style="font-size: 1.15rem; letter-spacing: -0.01em;">Ledger</h4>
                    </div>

                    <h1 class="fw-bold text-white mb-3" style="font-size: 26px; line-height: 1.3; letter-spacing: -0.02em;">
                        Two-Factor Security Verification
                    </h1>
                    <p class="text-white-80 small mb-4" style="opacity: 0.85; font-size: 14px; line-height: 1.6; max-width: 44ch;">
                        A 6-digit One-Time Password (OTP) has been issued for <strong>{{ $user->name ?? 'User' }}</strong> to complete session authorization.
                    </p>
                </div>

                <div class="pt-4 border-top border-white-10" style="border-color: rgba(255,255,255,0.15) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-shield-halved fs-3 text-warning"></i>
                        <div>
                            <div class="fw-bold text-white small">Multi-Factor Account Protection</div>
                            <div class="small text-white-50" style="font-size: 12px; opacity: 0.75;">AES-256 Encrypted Session Handshake</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM COLUMN -->
            <div class="col-lg-6 login-form-side">
                <div class="text-center text-lg-start mb-4">
                    <h3 class="fw-bold mb-1 text-theme-primary" style="font-size: 22px;">Enter Security Code</h3>
                    <p class="text-theme-muted small mb-0" style="font-size: 13px;">Verify the 6-digit OTP code sent to your account.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success py-2 small mb-3 rounded-3" style="background: #E7F6EE; color: #14804A; border: 1px solid #A6E0C1;">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info py-2 small mb-3 rounded-3">{{ session('info') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2 small mb-3 rounded-3">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.otp.verify') }}" method="POST" autocomplete="off">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium small text-theme-muted mb-2">6-Digit One-Time Password (OTP) *</label>
                        <input type="text" id="otpCode" name="otp_code" class="form-control" placeholder="000000" maxlength="6" required autofocus autocomplete="off">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-medium shadow-sm mb-3">
                        <i class="fa-solid fa-shield-check me-1"></i> Verify & Sign In
                    </button>
                </form>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: var(--border-color) !important;">
                    <form action="{{ route('login.otp.resend') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 text-decoration-none small fw-semibold" style="color: #0F7A68; font-size: 13px;">
                            <i class="fa-solid fa-rotate me-1"></i> Resend OTP Code
                        </button>
                    </form>

                    <a href="{{ route('login') }}" class="small text-muted text-decoration-none" style="font-size: 13px;">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function applyGlobalTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);

            const themeToggleIcon = document.getElementById('themeToggleIcon');
            const themeToggleText = document.getElementById('themeToggleText');

            if (theme === 'dark') {
                if (themeToggleIcon) themeToggleIcon.className = 'fa-solid fa-sun text-warning me-1';
                if (themeToggleText) themeToggleText.textContent = 'Light Mode';
            } else {
                if (themeToggleIcon) themeToggleIcon.className = 'fa-solid fa-moon me-1';
                if (themeToggleText) themeToggleText.textContent = 'Dark Mode';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'light';
            applyGlobalTheme(currentTheme);

            const themeToggleBtn = document.getElementById('themeToggleBtn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const activeTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                    const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
                    applyGlobalTheme(newTheme);
                });
            }
        });
    </script>
</body>
</html>
