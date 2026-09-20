<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Smart Supply Chain System</title>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
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
            --preset-bg: #F0F4F2;
            --preset-border: #C9D2CD;
            --primary: #0F7A68;
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
            --preset-bg: #24322D;
            --preset-border: #3A4A44;
            --primary: #3FB39B;
            --accent-amber: #F5AC3D;
        }

        /* Dark Mode High-Contrast Overrides */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] input,
        [data-theme="dark"] select {
            background-color: #0E1512 !important;
            border-color: #3A4A44 !important;
            color: #F0F4F2 !important;
        }

        [data-theme="dark"] .form-select option {
            background-color: #0E1512 !important;
            color: #F0F4F2 !important;
        }

        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] .form-select::placeholder,
        [data-theme="dark"] input::placeholder,
        [data-theme="dark"] ::placeholder {
            color: #A3AFA9 !important;
            opacity: 0.85 !important;
        }

        [data-theme="dark"] .input-group-text {
            background-color: #24322D !important;
            border-color: #3A4A44 !important;
            color: #7FCFBE !important;
        }

        [data-theme="dark"] .form-label,
        [data-theme="dark"] .form-check-label,
        [data-theme="dark"] .text-theme-muted,
        [data-theme="dark"] .text-theme-primary,
        [data-theme="dark"] label,
        [data-theme="dark"] p,
        [data-theme="dark"] span {
            color: #F0F4F2 !important;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            transition: background-color 0.2s ease, color 0.2s ease;
            position: relative;
        }
        .register-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(14,21,18,.10), 0 4px 6px -4px rgba(14,21,18,.10);
            overflow: hidden;
            width: 100%;
            max-width: 520px;
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
        .ai-badge {
            background: #E7F6EE;
            color: #14804A;
            border: 1px solid #A6E0C1;
            padding: 0.3rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .form-control, .form-select {
            background-color: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--input-text) !important;
            border-radius: 8px !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg) !important;
            border-color: #17957F !important;
            color: var(--input-text) !important;
            box-shadow: 0 0 0 2px rgba(15,122,104,.30) !important;
        }
        .input-group-text {
            background-color: var(--input-addon-bg) !important;
            border-color: var(--input-border) !important;
            color: #0F7A68 !important;
            border-top-left-radius: 8px !important;
            border-bottom-left-radius: 8px !important;
        }
        .btn-emerald, .btn-primary {
            background: #0F7A68 !important;
            color: #ffffff !important;
            font-weight: 500 !important;
            border: 1px solid #0F7A68 !important;
            border-radius: 8px !important;
            box-shadow: 0 1px 2px rgba(14,21,18,.04), 0 1px 3px rgba(14,21,18,.06);
            transition: all 0.15s ease-in-out;
        }
        .btn-emerald:hover, .btn-primary:hover {
            background: #0D6355 !important;
            border-color: #0D6355 !important;
            color: #ffffff !important;
        }
        .text-theme-primary {
            color: var(--text-primary) !important;
        }
        .text-theme-muted {
            color: var(--text-secondary) !important;
        }
        .toggle-password-btn {
            background-color: var(--input-addon-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text-secondary) !important;
            border-top-right-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }
            border-color: var(--input-border) !important;
            color: var(--text-secondary) !important;
        }
        .toggle-password-btn:hover {
            background-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }
        [data-theme="dark"] .toggle-password-btn {
            background-color: #1E293B !important;
            border-color: #475569 !important;
            color: #34D399 !important;
        }
        [data-theme="dark"] .toggle-password-btn:hover {
            background-color: #334155 !important;
            color: #FFFFFF !important;
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

        @media (max-width: 991.98px) {
            .login-hero-side {
                padding: 2.5rem 2rem;
            }
            .login-form-side {
                padding: 2.5rem 1.5rem;
            }
        }

        .tnum { font-variant-numeric: tabular-nums; }

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
                        Smart supply chain, clearly managed
                    </h1>
                    <p class="text-white-80 small mb-4" style="opacity: 0.85; font-size: 14px; line-height: 1.6; max-width: 44ch;">
                        One system for inventory tracking, warehouse optimization, procurement pipelines, and Scikit-Learn AI predictions.
                    </p>
                </div>

                <div class="pt-4 border-top border-white-10" style="border-color: rgba(255,255,255,0.15) !important;">
                    <div class="row g-3 text-start">
                        <div class="col-4">
                            <div class="text-uppercase" style="font-size: 10px; font-weight: 600; letter-spacing: 0.06em; opacity: 0.7;">PRODUCTS MANAGED</div>
                            <div class="tnum fw-bold mt-1" style="font-size: 20px;">1,290</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase" style="font-size: 10px; font-weight: 600; letter-spacing: 0.06em; opacity: 0.7;">STORAGE HUBS</div>
                            <div class="tnum fw-bold mt-1" style="font-size: 20px;">12</div>
                        </div>
                        <div class="col-4">
                            <div class="text-uppercase" style="font-size: 10px; font-weight: 600; letter-spacing: 0.06em; opacity: 0.7;">AI ACCURACY</div>
                            <div class="tnum fw-bold mt-1" style="font-size: 20px;">98.4%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT FORM COLUMN -->
            <div class="col-lg-6 login-form-side">
                <div class="text-center text-lg-start mb-4">
                    <h3 class="fw-bold mb-1 text-theme-primary" style="font-size: 22px;">Create an account</h3>
                    <p class="text-theme-muted small mb-0" style="font-size: 13px;">Sign up for an Administrator, Warehouse, or Procurement account.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 small mb-3 rounded-3">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" autocomplete="off">
                    @csrf
                    <!-- Anti-autofill dummy inputs to prevent browser prefilling credentials -->
                    <input type="text" name="fake_username_remembered" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" aria-hidden="true">
                    <input type="password" name="fake_password_remembered" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" aria-hidden="true">
                    
                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">Full Name *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                            <input type="text" id="nameInput" name="name" class="form-control" placeholder="Enter your full name" required value="{{ old('name') }}">
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">Username <span class="text-muted fw-normal">(for system login)</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-at"></i></span>
                            <input type="text" id="usernameInput" name="username" class="form-control" placeholder="Enter username" value="{{ old('username') }}">
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">Email Address *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" id="emailInput" name="email" class="form-control" placeholder="Enter your email address" required value="{{ old('email') }}">
                        </div>
                    </div>

                    <!-- System Access Role -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">Account Access Role *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-shield-halved"></i></span>
                            <select id="roleSelect" name="role_id" class="form-select" required>
                                <option value="" disabled selected>Select account role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }} ({{ $role->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="passwordInput" name="password" class="form-control" placeholder="At least 6 characters" required>
                            <button class="btn btn-outline-secondary toggle-password-btn" type="button" onclick="togglePasswordVisibility('passwordInput', this)" title="Show / Hide Password" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label fw-medium small text-theme-muted">Confirm Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-check-double"></i></span>
                            <input type="password" id="confirmPasswordInput" name="password_confirmation" class="form-control" placeholder="Confirm your password" required>
                            <button class="btn btn-outline-secondary toggle-password-btn" type="button" onclick="togglePasswordVisibility('confirmPasswordInput', this)" title="Show / Hide Password" aria-label="Toggle password confirmation visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- CAPTCHA Verification Section -->
                    <div class="p-3 rounded-3 mb-4 border" style="background: var(--neutral-50); border-color: var(--border-color) !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-semibold small text-theme-muted mb-0">
                                <i class="fa-solid fa-shield-halved text-success me-1"></i> Security CAPTCHA
                            </label>
                            <span class="badge" style="background: #E7F6EE; color: #14804A; border: 1px solid #A6E0C1; font-size: 0.68rem;">Bot Protection</span>
                        </div>
                        
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                            <div class="overflow-hidden rounded-3 border shadow-sm" style="background: var(--input-bg);">
                                <img id="captchaImg" src="{{ route('captcha.image') }}" alt="Distorted CAPTCHA Verification Image" style="height: 48px; width: 190px; display: block; cursor: pointer;" onclick="refreshCaptcha()" title="Click image to refresh CAPTCHA">
                            </div>
                            <button type="button" id="refreshCaptchaBtn" onclick="refreshCaptcha()" class="btn btn-outline-secondary btn-sm px-2.5 py-2 rounded-3 d-flex align-items-center gap-1" aria-label="Refresh CAPTCHA Code" title="Generate a new CAPTCHA code">
                                <i class="fa-solid fa-rotate-right me-1" id="refreshIcon"></i>
                                <span class="small fw-medium">Refresh</span>
                            </button>
                        </div>

                        <div class="input-group mb-1">
                            <span class="input-group-text"><i class="fa-solid fa-shield"></i></span>
                            <input type="text" id="captchaInput" name="captcha" class="form-control text-uppercase fw-bold" placeholder="Enter security code" required autocomplete="off" maxlength="8" style="letter-spacing: 1.5px;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-medium shadow-sm mb-3">
                        Create account
                    </button>

                    <div class="text-center pt-2">
                        <span class="text-theme-muted small" style="font-size: 13px;">Already have an account?</span>
                        <a href="{{ route('login') }}" class="small fw-semibold text-decoration-none ms-1" style="color: #0F7A68; font-size: 13px;">
                            Sign in here
                        </a>
                    </div>
                </form>
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

            const captchaImg = document.getElementById('captchaImg');
            if (captchaImg) {
                captchaImg.src = "{{ route('captcha.image') }}?theme=" + theme + "&t=" + Date.now();
            }
        }

        function refreshCaptcha() {
            const captchaImg = document.getElementById('captchaImg');
            const captchaInput = document.getElementById('captchaInput');
            const refreshIcon = document.getElementById('refreshIcon');

            if (refreshIcon) refreshIcon.classList.add('fa-spin');

            const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'light';
            if (captchaImg) {
                captchaImg.src = "{{ route('captcha.image') }}?theme=" + currentTheme + "&t=" + Date.now();
            }
            if (captchaInput) {
                captchaInput.value = '';
                captchaInput.focus();
            }

            setTimeout(() => {
                if (refreshIcon) refreshIcon.classList.remove('fa-spin');
            }, 350);
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

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }
    </script>
</body>
</html>
