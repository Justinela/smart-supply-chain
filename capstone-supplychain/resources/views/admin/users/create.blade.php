@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-user-plus text-primary me-2"></i> Create System User</h3>
        <p class="text-muted small mb-0">Add a new user account with role-based access permissions.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to User List
    </a>
</div>

<div class="card shadow-sm col-lg-8 mx-auto">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Account Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-select" required>
                        <option value="">-- Select System Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }} ({{ $role->description }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Account Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveSwitch" checked>
                        <label class="form-check-input-label fw-semibold" for="isActiveSwitch">Active Account</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                </div>

                <!-- CAPTCHA Verification Section -->
                <div class="col-12 mt-4">
                    <div class="p-3 rounded-3 border" style="background: var(--bg-hover); border-color: var(--border-color) !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-bold small text-muted mb-0">
                                <i class="fa-solid fa-shield-halved text-primary me-1"></i> CAPTCHA Verification
                            </label>
                            <span class="badge bg-secondary-subtle text-muted border" style="font-size: 0.68rem;">Human Security Check</span>
                        </div>
                        
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2.5">
                            <div class="overflow-hidden rounded-3 border shadow-sm" style="background: var(--input-bg);">
                                <img id="captchaImg" src="{{ route('captcha.image') }}" alt="Distorted CAPTCHA Verification Image" style="height: 52px; width: 200px; display: block; cursor: pointer;" onclick="refreshCaptcha()" title="Click image to refresh CAPTCHA">
                            </div>
                            <button type="button" id="refreshCaptchaBtn" onclick="refreshCaptcha()" class="btn btn-outline-secondary btn-sm px-3 py-2.5 rounded-3 d-flex align-items-center gap-1.5" aria-label="Refresh CAPTCHA Code" title="Can't read the CAPTCHA? Click to generate a new code">
                                <i class="fa-solid fa-rotate-right me-1" id="refreshIcon"></i>
                                <span class="fw-semibold">Refresh CAPTCHA</span>
                            </button>
                        </div>

                        <div class="input-group mb-1">
                            <span class="input-group-text"><i class="fa-solid fa-font"></i></span>
                            <input type="text" id="captchaInput" name="captcha" class="form-control text-uppercase fw-bold" placeholder="Enter characters shown above" required autocomplete="off" maxlength="8" style="letter-spacing: 1.5px;">
                        </div>
                        <div class="text-muted small" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-circle-info me-1 text-primary"></i> Can't read the CAPTCHA? Click <strong>Refresh CAPTCHA</strong> to generate a new code.
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary px-4"><i class="fa-solid fa-check me-1"></i> Create User Account</button>
            </div>
        </form>
    </div>
</div>

<script>
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
</script>
@endsection
