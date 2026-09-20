<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Smart Supply Chain System</title>
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
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif !important;
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

        @media (max-width: 991.98px) {
            .login-hero-side {
                padding: 2.5rem 2rem;
            }
            .login-form-side {
                padding: 2.5rem 1.5rem;
            }
        }

        .form-control, .form-select {
            background-color: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--input-text) !important;
            border-radius: 8px !important;
            padding: 0.6rem 0.9rem !important;
            font-size: 0.9rem !important;
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg) !important;
            color: var(--input-text) !important;
            border-color: #17957F !important;
            box-shadow: 0 0 0 2px rgba(15,122,104,.30) !important;
        }

        .input-group-text {
            background-color: var(--input-addon-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text-secondary) !important;
            border-top-left-radius: 8px !important;
            border-bottom-left-radius: 8px !important;
        }

        .text-theme-primary {
            color: var(--text-primary) !important;
        }

        .text-theme-muted {
            color: var(--text-secondary) !important;
        }

        .btn-primary {
            background: #0F7A68 !important;
            border: 1px solid #0F7A68 !important;
            color: #FFFFFF !important;
            box-shadow: 0 1px 2px rgba(14,21,18,.04), 0 1px 3px rgba(14,21,18,.06) !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            transition: all 0.15s ease-in-out !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background: #0D6355 !important;
            border-color: #0D6355 !important;
            transform: translateY(-1px) !important;
            color: #FFFFFF !important;
        }

        .btn-outline-primary {
            border-color: var(--primary) !important;
            color: var(--primary) !important;
            background: transparent !important;
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background: var(--primary) !important;
            color: #FFFFFF !important;
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

        [data-theme="dark"] .form-control::placeholder,
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

        .toggle-password-btn {
            background-color: var(--input-addon-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text-secondary) !important;
            border-top-right-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }

        .toggle-password-btn:hover {
            background-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        [data-theme="dark"] .toggle-password-btn {
            background-color: #24322D !important;
            border-color: #3A4A44 !important;
            color: #7FCFBE !important;
        }

        .theme-toggle-fixed {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
        }

        .bot-badge {
            background: #E7F6EE;
            color: #14804A;
            border: 1px solid #A6E0C1;
            font-size: 0.72rem;
            font-weight: 500;
        }
        [data-theme="dark"] .bot-badge {
            background: rgba(63, 179, 155, 0.15);
            color: #3FB39B;
            border-color: rgba(63, 179, 155, 0.3);
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
                        <span class="mark" style="width:30px; height:30px; border-radius:8px; background:#E8940F; color:#063831; display:grid; place-items:center; flex-shrink:0;">
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
                <div class="text-start mb-4">
                    <div class="d-inline-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill px-3 py-1.5 bot-badge d-flex align-items-center gap-1.5">
                            <i class="fa-solid fa-shield-halved"></i> Security Portal
                        </span>
                    </div>
                    <h3 class="fw-bold mb-1 text-theme-primary" style="font-size: 22px;">Change Account Password</h3>
                    <p class="text-theme-muted small mb-0" style="font-size: 13px;">Update your system access credentials securely.</p>
                </div>

                @if(session('info'))
                    <div class="alert alert-info py-2 small mb-3 rounded-3" style="background: #EAF3FF; color: #1E50A2; border: 1px solid #B8D5FF;">
                        <i class="fa-solid fa-circle-info me-1"></i> {{ session('info') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success py-2 small mb-3 rounded-3" style="background: #E7F6EE; color: #14804A; border: 1px solid #A6E0C1;">
                        <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2 small mb-3 rounded-3" style="background: #FDF2F2; color: #9B1C1C; border: 1px solid #F8B4B4;">
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Dynamic AJAX Alert Box -->
                <div id="otpAlertContainer"></div>

                <form id="changePasswordForm" action="{{ route('change_password') }}" method="POST" autocomplete="off">
                    @csrf
                    <!-- Anti-autofill dummy inputs to prevent browser prefilling credentials -->
                    <input type="text" name="fake_username_remembered" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" aria-hidden="true">
                    <input type="password" name="fake_password_remembered" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" aria-hidden="true">
                    
                    <!-- Email Address or Username -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">Email Address or Username *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-user-gear"></i></span>
                            <input type="text" id="identity" name="identity" class="form-control" placeholder="Enter email or username" required value="{{ old('identity', Auth::check() ? Auth::user()->email : '') }}">
                        </div>
                    </div>

                    @auth
                    <!-- Current Password (Required when logged in) -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">Current Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock-open"></i></span>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password" required>
                            <button class="btn btn-outline-secondary toggle-password-btn" type="button" onclick="togglePasswordVisibility('current_password', this)" title="Show / Hide Password" aria-label="Toggle current password visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @endauth

                    <!-- Security Verification Code (OTP Token) -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted d-flex justify-content-between align-items-center">
                            <span>Security Verification Code (OTP) *</span>
                            <span class="badge rounded-pill bg-light text-success border border-success-subtle" style="font-size: 0.68rem;"><i class="fa-solid fa-envelope-circle-check me-1"></i>Email OTP</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-shield-cat"></i></span>
                            <input type="text" id="token" name="token" class="form-control text-uppercase fw-bold" placeholder="8-character code" required maxlength="8" style="letter-spacing: 1.5px;">
                            <button type="button" id="btnSendOtp" class="btn btn-outline-primary px-3 fw-medium d-flex align-items-center gap-1" style="border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important;">
                                <i class="fa-solid fa-paper-plane" id="sendOtpIcon"></i>
                                <span id="sendOtpText">Send Code</span>
                            </button>
                        </div>
                        <div class="text-theme-muted mt-1" style="font-size: 0.73rem;">
                            <i class="fa-solid fa-circle-info me-1" style="color: var(--primary);"></i> Click 'Send Code' to receive an 8-character verification code via email.
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label class="form-label fw-medium small text-theme-muted">New Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="At least 6 characters" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password-btn" type="button" onclick="togglePasswordVisibility('password', this)" title="Show / Hide Password" aria-label="Toggle new password visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-4">
                        <label class="form-label fw-medium small text-theme-muted">Confirm New Password *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-check-double"></i></span>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat new password" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password-btn" type="button" onclick="togglePasswordVisibility('password_confirmation', this)" title="Show / Hide Password" aria-label="Toggle password confirmation visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="btnUpdatePassword" class="btn btn-primary w-100 py-2.5 rounded-3 mb-3 fw-medium">
                        <i class="fa-solid fa-key me-2"></i> Update Password Now
                    </button>
                </form>

                <!-- Post-Password-Change Choices Container -->
                <div id="passwordChoicesContainer" class="{{ session('password_changed_options') ? '' : 'd-none' }} text-center py-3">
                    <div class="mb-3">
                        <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 64px; height: 64px; background: #E7F6EE; color: #14804A; border: 2px solid #A6E0C1;">
                            <i class="fa-solid fa-circle-check fa-2x"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold mb-2 text-theme-primary">Password Changed Successfully!</h4>
                    <p class="text-theme-muted small mb-4">Your account password has been updated. Please choose how you would like to proceed:</p>
                    
                    <div class="d-grid gap-2.5 max-w-sm mx-auto">
                        <!-- Option 1: Continue Session / Go to Dashboard -->
                        <a href="{{ route('dashboard') }}" class="btn btn-primary py-2.5 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-house"></i> Continue Session (Go to Dashboard)
                        </a>

                        <!-- Option 2: Sign Out & Log In Again -->
                        <form action="{{ route('logout') }}" method="POST" class="w-100 m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100 py-2.5 rounded-3 fw-medium d-flex align-items-center justify-content-center gap-2" style="border-color: var(--border-color); color: var(--text-primary);">
                                <i class="fa-solid fa-right-to-bracket"></i> Log In Again
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center pt-3 border-top border-secondary border-opacity-25 d-flex justify-content-center gap-3">
                    @auth
                    <a href="{{ route('dashboard') }}" class="small fw-semibold text-decoration-none" style="color: var(--primary);">
                        <i class="fa-solid fa-house me-1"></i>Dashboard
                    </a>
                    @endauth
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

            // --- OTP AJAX & Cooldown Timer ---
            const btnSendOtp = document.getElementById('btnSendOtp');
            const sendOtpIcon = document.getElementById('sendOtpIcon');
            const sendOtpText = document.getElementById('sendOtpText');
            const identityInput = document.getElementById('identity');
            const otpAlertContainer = document.getElementById('otpAlertContainer');

            function showAlert(type, message) {
                if (!otpAlertContainer) return;
                const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';
                const bgStyle = type === 'success' ? 'background: #E7F6EE; color: #14804A; border: 1px solid #A6E0C1;' : 'background: #FDF2F2; color: #9B1C1C; border: 1px solid #F8B4B4;';
                
                otpAlertContainer.innerHTML = `
                    <div class="alert alert-${type} py-2 px-3 small mb-3 rounded-3 d-flex align-items-center justify-content-between" style="${bgStyle}">
                        <div><i class="fa-solid ${iconClass} me-2"></i> ${message}</div>
                        <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.remove()" aria-label="Close"></button>
                    </div>
                `;
            }

            function startOtpCooldown(duration = 60) {
                let cooldown = duration;
                btnSendOtp.disabled = true;
                if (sendOtpIcon) sendOtpIcon.className = 'fa-solid fa-clock me-1';
                
                const interval = setInterval(() => {
            let cooldownInterval = null;

            const tokenInput = document.getElementById('token');
            if (tokenInput) {
                tokenInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 8);
                });
            }

            function showAlert(type, message) {
                if (!otpAlertContainer) return;
                const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';
                const bgStyle = type === 'success' ? 'background: #E7F6EE; color: #14804A; border: 1px solid #A6E0C1;' : 'background: #FDF2F2; color: #9B1C1C; border: 1px solid #F8B4B4;';
                
                otpAlertContainer.innerHTML = `
                    <div class="alert alert-${type} py-2 px-3 small mb-3 rounded-3 d-flex align-items-center justify-content-between shadow-sm" style="${bgStyle}">
                        <div><i class="fa-solid ${iconClass} me-2"></i> ${message}</div>
                        <button type="button" class="btn-close btn-close-sm" onclick="this.parentElement.remove()" aria-label="Close"></button>
                    </div>
                `;
                otpAlertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function startOtpCooldown(duration = 60) {
                let cooldown = duration;
                if (cooldownInterval) clearInterval(cooldownInterval);
                btnSendOtp.disabled = true;
                if (sendOtpIcon) sendOtpIcon.className = 'fa-solid fa-clock me-1';
                
                cooldownInterval = setInterval(() => {
                    cooldown--;
                    if (sendOtpText) sendOtpText.textContent = `Resend in ${cooldown}s`;
                    if (cooldown <= 0) {
                        clearInterval(cooldownInterval);
                        cooldownInterval = null;
                        btnSendOtp.disabled = false;
                        if (sendOtpIcon) sendOtpIcon.className = 'fa-solid fa-paper-plane me-1';
                        if (sendOtpText) sendOtpText.textContent = 'Send Code';
                    }
                }, 1000);
            }

            if (btnSendOtp) {
                btnSendOtp.addEventListener('click', function() {
                    const identityVal = identityInput ? identityInput.value.trim() : '';
                    if (!identityVal) {
                        showAlert('danger', 'Please enter your Email Address or Username first.');
                        if (identityInput) identityInput.focus();
                        return;
                    }

                    btnSendOtp.disabled = true;
                    if (sendOtpIcon) sendOtpIcon.className = 'fa-solid fa-spinner fa-spin me-1';
                    if (sendOtpText) sendOtpText.textContent = 'Sending...';

                    fetch('{{ route("change_password.send_code") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ identity: identityVal })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        const data = res.body;
                        if (data.success) {
                            showAlert('success', data.message);
                            startOtpCooldown(60);
                        } else {
                            showAlert('danger', data.message || 'Failed to send verification security code.');
                            btnSendOtp.disabled = false;
                            if (sendOtpIcon) sendOtpIcon.className = 'fa-solid fa-paper-plane me-1';
                            if (sendOtpText) sendOtpText.textContent = 'Send Code';
                        }
                    })
                    .catch(err => {
                        showAlert('danger', 'An unexpected network error occurred. Please try again.');
                        btnSendOtp.disabled = false;
                        if (sendOtpIcon) sendOtpIcon.className = 'fa-solid fa-paper-plane me-1';
                        if (sendOtpText) sendOtpText.textContent = 'Send Code';
                    });
                });
            }

            // --- Change Password Form Submit Handler ---
            const changePasswordForm = document.getElementById('changePasswordForm');
            const passwordChoicesContainer = document.getElementById('passwordChoicesContainer');
            const btnUpdatePassword = document.getElementById('btnUpdatePassword');

            if (changePasswordForm) {
                changePasswordForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const pass = document.getElementById('password')?.value || '';
                    const passConfirm = document.getElementById('password_confirmation')?.value || '';
                    const tokenVal = tokenInput?.value.trim() || '';

                    if (tokenVal.length !== 8) {
                        showAlert('danger', 'The verification security code must be exactly 8 characters long.');
                        if (tokenInput) tokenInput.focus();
                        return;
                    }

                    if (pass.length < 6) {
                        showAlert('danger', 'New password must be at least 6 characters long.');
                        document.getElementById('password')?.focus();
                        return;
                    }

                    if (pass !== passConfirm) {
                        showAlert('danger', 'New password confirmation does not match.');
                        document.getElementById('password_confirmation')?.focus();
                        return;
                    }

                    if (btnUpdatePassword) {
                        btnUpdatePassword.disabled = true;
                        btnUpdatePassword.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Updating Password...';
                    }

                    const formData = new FormData(changePasswordForm);

                    fetch('{{ route("change_password") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        const data = res.body;
                        if (res.status === 200 && data.success) {
                            showAlert('success', data.message || 'Password updated successfully!');
                            changePasswordForm.classList.add('d-none');
                            if (passwordChoicesContainer) {
                                passwordChoicesContainer.classList.remove('d-none');
                                passwordChoicesContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        } else {
                            let errorMsg = data.message || 'Validation failed. Please check your inputs.';
                            if (data.errors) {
                                const firstKey = Object.keys(data.errors)[0];
                                if (firstKey && data.errors[firstKey][0]) {
                                    errorMsg = data.errors[firstKey][0];
                                }
                            }
                            showAlert('danger', errorMsg);
                            if (btnUpdatePassword) {
                                btnUpdatePassword.disabled = false;
                                btnUpdatePassword.innerHTML = '<i class="fa-solid fa-key me-2"></i> Update Password Now';
                            }
                        }
                    })
                    .catch(err => {
                        showAlert('danger', 'An unexpected network error occurred. Please try again.');
                        if (btnUpdatePassword) {
                            btnUpdatePassword.disabled = false;
                            btnUpdatePassword.innerHTML = '<i class="fa-solid fa-key me-2"></i> Update Password Now';
                        }
                    });
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
