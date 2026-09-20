<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Supply Chain') - Enterprise Platform</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root, [data-theme="light"] {
            --primary-50: #EFFAF7;  --primary-100: #DBF3ED; --primary-200: #B4E5DA;
            --primary-300: #7FCFBE; --primary-400: #3FB39B; --primary-500: #17957F;
            --primary-600: #0F7A68; --primary-700: #0D6355; --primary-800: #0A4D42;
            --primary-900: #063831; --primary-950: #04231F;

            --accent-100: #FEF0D6; --accent-300: #FAC978; --accent-400: #F5AC3D;
            --accent-500: #E8940F; --accent-600: #C2760B; --accent-700: #9A5B08;

            --neutral-50: #F7FAF9;  --neutral-100: #F0F4F2; --neutral-200: #E2E8E5;
            --neutral-300: #C9D2CD; --neutral-400: #A3AFA9; --neutral-500: #7B8A84;
            --neutral-600: #5A6B64; --neutral-700: #3A4A44; --neutral-800: #24322D;
            --neutral-900: #16211D; --neutral-950: #0E1512;

            --success: #14804A; --warning: #B54708; --danger: #B42318; --info: #1570EF;

            --shadow-card: 0 1px 2px rgba(14,21,18,.04), 0 1px 3px rgba(14,21,18,.06);
            --shadow-flyout: 0 10px 15px -3px rgba(14,21,18,.10), 0 4px 6px -4px rgba(14,21,18,.10);

            --bg-primary: #F7FAF9;
            --bg-surface: #FFFFFF;
            --bg-secondary: #F0F4F2;
            --bg-sidebar: #063831;
            --bg-navbar: #FFFFFF;
            --text-primary: #24322D;
            --text-secondary: #5A6B64;
            --text-muted: #7B8A84;
            --border-color: #E2E8E5;
            --primary: #0F7A68;
            --primary-gradient: linear-gradient(135deg, #0F7A68 0%, #0D6355 100%);
            --accent: #E8940F;
            --teal-smart: #0F7A68;

            --card-bg: #FFFFFF;
            --card-header-bg: #FFFFFF;
            --card-footer-bg: #F7FAF9;
            --table-head-bg: #F0F4F2;
            --table-row-hover: #EFFAF7;
            --input-bg: #FFFFFF;
            --input-border: #C9D2CD;
            --input-text: #24322D;

            --sidebar-bg: #063831;
            --sidebar-text: #DBF3ED;
            --sidebar-header: #7FCFBE;
            --sidebar-hover-bg: rgba(13, 99, 85, 0.4);
            --sidebar-hover-text: #FFFFFF;
            --sidebar-active-bg: #0D6355;
            --sidebar-active-text: #FFFFFF;
            --sidebar-border: #0A4D42;

            --badge-dark-bg: #24322D;
            --badge-dark-text: #FFFFFF;
            --badge-light-bg: #E7F6EE;
            --badge-light-text: #14804A;
            --badge-secondary-bg: #0F7A68;
            --badge-secondary-text: #FFFFFF;

            --bg-primary-subtle: #EFFAF7;
            --text-primary-subtle: #0D6355;
            --bg-info-subtle: #EAF2FE;
            --text-info-subtle: #1570EF;
            --bg-success-subtle: #E7F6EE;
            --text-success-subtle: #14804A;
            --bg-warning-subtle: #FEF0D6;
            --text-warning-subtle: #B54708;
            --bg-danger-subtle: #FEECEA;
            --text-danger-subtle: #B42318;
            --bg-purple-subtle: #DBF3ED;
            --text-purple-subtle: #063831;
            --bg-secondary-subtle: #F0F4F2;
            --text-secondary-subtle: #5A6B64;

            --ai-banner-bg: linear-gradient(135deg, #EFFAF7 0%, #DBF3ED 100%);
            --ai-banner-text: #063831;
            --ai-banner-card-bg: #FFFFFF;
            --ai-banner-card-border: #B4E5DA;
            --ai-banner-muted: #0D6355;

            --btn-toggle-bg: #F0F4F2;
            --btn-toggle-border: #C9D2CD;
            --btn-toggle-text: #24322D;
        }

        [data-theme="dark"] {
            --primary-50: #EFFAF7;  --primary-100: #DBF3ED; --primary-200: #B4E5DA;
            --primary-300: #7FCFBE; --primary-400: #3FB39B; --primary-500: #17957F;
            --primary-600: #0F7A68; --primary-700: #0D6355; --primary-800: #0A4D42;
            --primary-900: #063831; --primary-950: #04231F;

            --accent-100: #FEF0D6; --accent-300: #FAC978; --accent-400: #F5AC3D;
            --accent-500: #E8940F; --accent-600: #C2760B; --accent-700: #9A5B08;

            --neutral-50: #F7FAF9;  --neutral-100: #F0F4F2; --neutral-200: #E2E8E5;
            --neutral-300: #C9D2CD; --neutral-400: #A3AFA9; --neutral-500: #7B8A84;
            --neutral-600: #5A6B64; --neutral-700: #3A4A44; --neutral-800: #24322D;
            --neutral-900: #16211D; --neutral-950: #0E1512;

            --success: #14804A; --warning: #B54708; --danger: #B42318; --info: #1570EF;

            --shadow-card: 0 1px 2px rgba(0,0,0,.2), 0 1px 3px rgba(0,0,0,.3);
            --shadow-flyout: 0 10px 15px -3px rgba(0,0,0,.4), 0 4px 6px -4px rgba(0,0,0,.4);

            --bg-primary: #0E1512;
            --bg-surface: #16211D;
            --bg-secondary: #24322D;
            --bg-sidebar: #04231F;
            --bg-navbar: #16211D;
            --text-primary: #F0F4F2;
            --text-secondary: #A3AFA9;
            --text-muted: #7B8A84;
            --border-color: #24322D;
            --primary: #3FB39B;
            --primary-gradient: linear-gradient(135deg, #0F7A68 0%, #3FB39B 100%);
            --accent: #F5AC3D;
            --teal-smart: #3FB39B;

            --card-bg: #16211D;
            --card-header-bg: #16211D;
            --card-footer-bg: #0E1512;
            --table-head-bg: #24322D;
            --table-row-hover: rgba(23, 149, 127, 0.15);
            --input-bg: #0E1512;
            --input-border: #3A4A44;
            --input-text: #F0F4F2;

            --sidebar-bg: #04231F;
            --sidebar-text: #B4E5DA;
            --sidebar-header: #7FCFBE;
            --sidebar-hover-bg: rgba(13, 99, 85, 0.5);
            --sidebar-hover-text: #FFFFFF;
            --sidebar-active-bg: #0D6355;
            --sidebar-active-text: #FFFFFF;
            --sidebar-border: #0A4D42;

            --badge-dark-bg: #24322D;
            --badge-dark-text: #F0F4F2;
            --badge-light-bg: #24322D;
            --badge-light-text: #F0F4F2;
            --badge-secondary-bg: #0F7A68;
            --badge-secondary-text: #FFFFFF;

            --bg-primary-subtle: rgba(15, 122, 104, 0.25);
            --text-primary-subtle: #B4E5DA;
            --bg-info-subtle: rgba(21, 112, 239, 0.2);
            --text-info-subtle: #B0CDFA;
            --bg-success-subtle: rgba(20, 128, 74, 0.2);
            --text-success-subtle: #A6E0C1;
            --bg-warning-subtle: rgba(181, 71, 8, 0.2);
            --text-warning-subtle: #F5D28A;
            --bg-danger-subtle: rgba(180, 35, 24, 0.2);
            --text-danger-subtle: #F5B5AE;
            --bg-purple-subtle: rgba(127, 207, 190, 0.2);
            --text-purple-subtle: #B4E5DA;
            --bg-secondary-subtle: rgba(90, 107, 100, 0.2);
            --text-secondary-subtle: #C9D2CD;

            --ai-banner-bg: linear-gradient(135deg, #063831 0%, #0A4D42 100%);
            --ai-banner-text: #F0F4F2;
            --ai-banner-card-bg: rgba(255, 255, 255, 0.05);
            --ai-banner-card-border: rgba(127, 207, 190, 0.2);
            --ai-banner-muted: #B4E5DA;

            --btn-toggle-bg: #24322D;
            --btn-toggle-border: #3A4A44;
            --btn-toggle-text: #F0F4F2;
        }


        .pagination .page-link {
            color: var(--text-primary) !important;
            background-color: var(--bg-surface) !important;
            border-color: var(--border-color) !important;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #FFFFFF !important;
        }
        .pagination .page-item.disabled .page-link {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-muted) !important;
        }

        body, button, input, select, textarea, .font-poppins, h1, h2, h3, h4, h5, h6, .navbar-brand, .nav-link, .card, .table {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        /* 3D MARQUEE ANIMATION STYLING */
        .marquee-3d-wrapper {
            perspective: 1200px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.08) 0%, rgba(6, 182, 212, 0.08) 50%, rgba(16, 185, 129, 0.08) 100%);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 1rem;
            padding: 0.65rem 0;
            box-shadow: 0 10px 30px -10px rgba(79, 70, 229, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.4);
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
        }

        [data-theme="dark"] .marquee-3d-wrapper {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 41, 59, 0.9) 100%);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 12px 35px -5px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .marquee-3d-stage {
            transform-style: preserve-3d;
            transform: rotateX(8deg) rotateY(-1deg) translateZ(0);
            display: flex;
            width: 100%;
        }

        .marquee-3d-track {
            display: flex;
            gap: 1.5rem;
            width: max-content;
            white-space: nowrap;
            animation: marquee3DLoop 26s linear infinite;
            will-change: transform;
        }

        @keyframes marquee3DLoop {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .marquee-3d-item {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.45rem 1.1rem;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 50rem;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.04);
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-primary);
        }

        .marquee-3d-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #FFFFFF;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .marquee-3d-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #4F46E5 0%, #06B6D4 50%, #10B981 100%);
            color: #FFFFFF !important;
            padding: 0.45rem 1.25rem;
            border-radius: 50rem;
            font-weight: 700;
            font-size: 0.88rem;
            letter-spacing: 0.4px;
            box-shadow: 0 4px 18px rgba(79, 70, 229, 0.4);
            transform-style: preserve-3d;
        }

        .marquee-3d-wrapper::before,
        .marquee-3d-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 70px;
            z-index: 2;
            pointer-events: none;
        }

        .marquee-3d-wrapper::before {
            left: 0;
            background: linear-gradient(to right, var(--bg-primary), transparent);
        }

        .marquee-3d-wrapper::after {
            right: 0;
            background: linear-gradient(to left, var(--bg-primary), transparent);
        }

        .enterprise-navbar {
            background-color: var(--bg-navbar) !important;
            border-bottom: 1px solid var(--border-color) !important;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        /* Card Component Styling */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 1.25rem !important;
            box-shadow: 0 10px 25px -5px rgba(124, 58, 237, 0.07), 0 4px 10px -3px rgba(0, 0, 0, 0.02) !important;
            background-color: var(--card-bg) !important;
            color: var(--text-primary) !important;
            margin-bottom: 1.5rem;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.2s ease, border-color 0.2s ease !important;
        }

        .card:hover {
            box-shadow: 0 14px 30px -5px rgba(124, 58, 237, 0.12), 0 6px 15px -3px rgba(0, 0, 0, 0.04) !important;
        }

        .card-header {
            background-color: var(--card-header-bg) !important;
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            font-weight: 700;
            padding: 1.1rem 1.35rem;
            border-top-left-radius: 1.25rem !important;
            border-top-right-radius: 1.25rem !important;
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .card-footer {
            background-color: var(--card-footer-bg) !important;
            border-top: 1px solid var(--border-color) !important;
            color: var(--text-secondary) !important;
            border-bottom-left-radius: 1.25rem !important;
            border-bottom-right-radius: 1.25rem !important;
        }

        .card-body {
            color: var(--text-primary) !important;
            padding: 1.35rem;
        }

        /* Sidebar Navigation Styling */
        .sidebar {
            min-height: calc(100vh - 60px);
            background-color: var(--sidebar-bg) !important;
            color: var(--sidebar-text) !important;
            border-right: 1px solid var(--sidebar-border) !important;
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .sidebar .section-header {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--sidebar-header) !important;
            padding: 1.25rem 1.25rem 0.45rem;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text) !important;
            font-weight: 400;
            font-size: 0.88rem;
            padding: 0.65rem 1.1rem;
            border-radius: 8px;
            margin: 0.2rem 0.6rem;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }

        .sidebar .nav-link i {
            font-size: 0.95rem;
            width: 1.25rem;
            text-align: center;
            color: var(--sidebar-header) !important;
            transition: transform 0.2s ease;
        }

        .sidebar .nav-link:hover {
            color: var(--sidebar-hover-text) !important;
            background-color: var(--sidebar-hover-bg) !important;
            transform: translateX(3px);
        }

        .sidebar .nav-link:hover i {
            color: #FFFFFF !important;
            transform: scale(1.1);
        }

        .sidebar .nav-link.active {
            color: #FFFFFF !important;
            background-color: var(--sidebar-active-bg) !important;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(13, 122, 104, 0.3) !important;
            border-left: 3px solid var(--accent-500) !important;
        }

        .sidebar .nav-link.active i {
            color: var(--accent-300) !important;
        }

        /* Buttons Styling Override */
        .btn-primary {
            background: var(--primary-600) !important;
            border: 1px solid var(--primary-600) !important;
            color: #FFFFFF !important;
            box-shadow: var(--shadow-card) !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            transition: all 0.15s ease-in-out !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background: var(--primary-700) !important;
            border-color: var(--primary-700) !important;
            transform: translateY(-1px) !important;
            box-shadow: var(--shadow-flyout) !important;
            color: #FFFFFF !important;
        }

        .btn-outline-primary, .btn-secondary, .btn-outline-secondary {
            background: var(--bg-surface) !important;
            color: var(--text-primary) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            transition: all 0.15s ease-in-out !important;
        }

        .btn-outline-primary:hover, .btn-secondary:hover, .btn-outline-secondary:hover {
            background: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }


        /* Table Styling */
        .table {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .table th, .table-light th, thead.table-light th, tfoot.table-light th {
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-secondary) !important;
            background-color: var(--table-head-bg) !important;
            border-bottom: 2px solid var(--border-color) !important;
        }

        .table-light, thead.table-light, tbody.table-light, tr.table-light {
            background-color: var(--table-head-bg) !important;
            color: var(--text-primary) !important;
        }

        .table td {
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
            background-color: transparent !important;
        }

        .table-hover tbody tr:hover {
            background-color: var(--table-row-hover) !important;
            color: var(--text-primary) !important;
        }

        /* Form Inputs, Labels & Selects */
        .form-label, label {
            color: var(--text-primary) !important;
            font-weight: 600;
        }

        .form-control, .form-select, textarea {
            background-color: var(--input-bg) !important;
            color: var(--input-text) !important;
            border-color: var(--input-border) !important;
        }

        .form-control::placeholder,
        .form-select::placeholder,
        textarea::placeholder,
        ::placeholder {
            color: var(--text-muted) !important;
            opacity: 0.75 !important;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] input,
        [data-theme="dark"] select,
        [data-theme="dark"] textarea {
            background-color: #0F172A !important;
            border-color: #334155 !important;
            color: #FFFFFF !important;
        }

        [data-theme="dark"] select option {
            background-color: #0F172A !important;
            color: #FFFFFF !important;
        }

        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] .form-select::placeholder,
        [data-theme="dark"] textarea::placeholder,
        [data-theme="dark"] ::placeholder {
            color: #E2E8F0 !important;
            opacity: 0.85 !important;
        }

        /* WebKit Browser Autofill text & background override in Dark Mode */
        [data-theme="dark"] input:-webkit-autofill,
        [data-theme="dark"] input:-webkit-autofill:hover, 
        [data-theme="dark"] input:-webkit-autofill:focus, 
        [data-theme="dark"] input:-webkit-autofill:active {
            -webkit-text-fill-color: #FFFFFF !important;
            -webkit-box-shadow: 0 0 0px 1000px #0F172A inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .form-control:focus, .form-select:focus, textarea:focus {
            background-color: var(--input-bg) !important;
            color: var(--input-text) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25) !important;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: var(--bg-secondary) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
            opacity: 0.85;
        }

        .input-group-text {
            background-color: var(--bg-secondary) !important;
            color: var(--text-secondary) !important;
            border-color: var(--border-color) !important;
        }

        select option {
            background-color: var(--input-bg);
            color: var(--input-text);
        }

        /* Utility Class Overrides for Adaptive Theme Synchronization */
        .bg-white {
            background-color: var(--bg-surface) !important;
            color: var(--text-primary) !important;
        }

        .bg-light {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
        }

        .bg-dark {
            background-color: var(--badge-dark-bg) !important;
            color: var(--badge-dark-text) !important;
        }

        .text-dark, .text-black {
            color: var(--text-primary) !important;
        }

        .text-muted, .text-secondary {
            color: var(--text-secondary) !important;
        }

        .text-purple {
            color: var(--purple) !important;
        }

        /* Subtle background & text helpers */
        .bg-primary-subtle {
            background-color: var(--bg-primary-subtle) !important;
            color: var(--text-primary-subtle) !important;
        }

        .bg-info-subtle {
            background-color: var(--bg-info-subtle) !important;
            color: var(--text-info-subtle) !important;
        }

        .bg-success-subtle {
            background-color: var(--bg-success-subtle) !important;
            color: var(--text-success-subtle) !important;
        }

        .bg-warning-subtle {
            background-color: var(--bg-warning-subtle) !important;
            color: var(--text-warning-subtle) !important;
        }

        .bg-danger-subtle {
            background-color: var(--bg-danger-subtle) !important;
            color: var(--text-danger-subtle) !important;
        }

        .bg-secondary-subtle {
            background-color: var(--bg-secondary-subtle) !important;
            color: var(--text-secondary-subtle) !important;
        }

        .bg-purple-subtle {
            background-color: var(--bg-purple-subtle) !important;
            color: var(--text-purple-subtle) !important;
        }

        /* Badge Overrides */
        .badge.bg-dark {
            background-color: var(--badge-dark-bg) !important;
            color: var(--badge-dark-text) !important;
        }

        .badge.bg-light {
            background-color: var(--badge-light-bg) !important;
            color: var(--badge-light-text) !important;
        }

        .badge.bg-secondary {
            background-color: var(--badge-secondary-bg) !important;
            color: var(--badge-secondary-text) !important;
        }

        .badge.bg-primary-subtle { color: var(--text-primary-subtle) !important; }
        .badge.bg-info-subtle { color: var(--text-info-subtle) !important; }
        .badge.bg-success-subtle { color: var(--text-success-subtle) !important; }
        .badge.bg-warning-subtle { color: var(--text-warning-subtle) !important; }
        .badge.bg-danger-subtle { color: var(--text-danger-subtle) !important; }
        .badge.bg-secondary-subtle { color: var(--text-secondary-subtle) !important; }
        .badge.bg-purple-subtle { color: var(--text-purple-subtle) !important; }

        /* Dropdowns, Modals, Alerts */
        .dropdown-menu {
            background-color: var(--bg-surface) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .dropdown-item {
            color: var(--text-primary) !important;
        }

        .dropdown-item:hover {
            background-color: var(--bg-secondary) !important;
            color: var(--primary) !important;
        }

        .dropdown-header {
            color: var(--text-secondary) !important;
        }

        .modal-content {
            background-color: var(--bg-surface) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3) !important;
        }

        .modal-header {
            background-color: var(--bg-surface) !important;
            border-bottom: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .modal-title {
            color: var(--text-primary) !important;
            font-weight: 700;
        }

        .modal-body {
            background-color: var(--bg-surface) !important;
            color: var(--text-primary) !important;
        }

        .modal-footer {
            background-color: var(--bg-surface) !important;
            border-top: 1px solid var(--border-color) !important;
        }

        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        [data-theme="dark"] .alert-success {
            background-color: rgba(34, 197, 94, 0.15) !important;
            border-color: rgba(34, 197, 94, 0.3) !important;
            color: #86EFAC !important;
        }

        [data-theme="dark"] .alert-info {
            background-color: rgba(56, 189, 248, 0.15) !important;
            border-color: rgba(56, 189, 248, 0.3) !important;
            color: #7DD3FC !important;
        }

        [data-theme="dark"] .alert-warning {
            background-color: rgba(245, 158, 11, 0.15) !important;
            border-color: rgba(245, 158, 11, 0.3) !important;
            color: #FDE68A !important;
        }

        [data-theme="dark"] .alert-danger {
            background-color: rgba(239, 68, 68, 0.15) !important;
            border-color: rgba(239, 68, 68, 0.3) !important;
            color: #FCA5A5 !important;
        }

        .list-group-item {
            background-color: var(--bg-surface) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        /* AI Insights Banner Theme Overrides */
        .ai-insights-card {
            background: var(--ai-banner-bg) !important;
            color: var(--ai-banner-text) !important;
            border: 1px solid var(--ai-banner-card-border) !important;
        }

        .ai-insights-card h5, 
        .ai-insights-card p, 
        .ai-insights-card li,
        .ai-insights-card .banner-text {
            color: var(--ai-banner-text) !important;
        }

        .ai-insights-box {
            background: var(--ai-banner-card-bg) !important;
            border: 1px solid var(--ai-banner-card-border) !important;
            border-radius: 0.5rem;
            color: var(--ai-banner-text) !important;
        }

        .ai-insights-box li {
            color: var(--ai-banner-text) !important;
        }

        .ai-insights-muted {
            color: var(--ai-banner-muted) !important;
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            background: var(--btn-toggle-bg);
            border: 1px solid var(--btn-toggle-border);
            color: var(--btn-toggle-text);
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .theme-toggle-btn:hover {
            background: var(--bg-secondary);
            border-color: var(--primary);
            color: var(--text-primary);
        }

        .ai-badge {
            background: linear-gradient(135deg, #059669 0%, #10B981 100%);
            color: #FFFFFF !important;
            padding: 0.3rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
            line-height: 1.2;
            vertical-align: middle;
        }

        [data-theme="dark"] .ai-badge {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: #FFFFFF !important;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.5);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.25rem;
        }

        .capacity-bar-container {
            height: 8px;
            background-color: var(--border-color);
            border-radius: 4px;
            overflow: hidden;
        }

        .sidebar-toggle-btn {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.35rem 0.65rem;
            border-radius: 0.375rem;
            font-size: 1.25rem;
            line-height: 1;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle-btn:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .sidebar.collapsed-desktop {
            display: none !important;
        }

        main.main-expanded {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            margin-left: 0 !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Enterprise Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm enterprise-navbar">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle-btn me-3" id="sidebarToggle" type="button" title="Toggle Navigation Sidebar" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a class="navbar-brand d-flex align-items-center gap-2 mb-0" href="{{ route('dashboard') }}">
                    <span class="mark" style="width:32px; height:32px; border-radius:8px; background: var(--accent-500); color: var(--primary-950); display:grid; place-items:center; flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 15h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 17"/>
                            <path d="m7 21 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"/>
                            <path d="m2 16 6 6"/><circle cx="16" cy="9" r="2.9"/><circle cx="6" cy="5" r="3"/>
                        </svg>
                    </span>
                    <span style="color: var(--neutral-800); font-weight:600; letter-spacing: -0.01em; font-size:1.15rem;">Ledger <span style="color: var(--neutral-500); font-weight:400; font-size:0.85rem;">Supply Chain</span></span>
                </a>
            </div>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <li class="nav-item">
                        <button class="theme-toggle-btn" id="themeToggleBtn" type="button" title="Toggle Light/Dark Theme" aria-label="Toggle Theme">
                            <i class="fa-solid fa-moon text-info" id="themeToggleIcon"></i>
                            <span id="themeToggleText">Dark Mode</span>
                        </button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" style="color: var(--text-primary) !important;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width:34px; height:34px; background: var(--primary);">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <span>{{ Auth::user()->name ?? 'User' }}</span>
                            <span class="badge bg-secondary text-light ms-1">{{ Auth::user()->role->display_name ?? 'User' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><h6 class="dropdown-header">Logged in as {{ Auth::user()->email ?? '' }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('password.change') }}">
                                    <i class="fa-solid fa-key text-primary"></i> Change Password
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3 col-lg-2 px-0 sidebar collapse d-md-block" id="sidebarMenu">
                <div class="py-2">
                    <!-- SECTION 1: SMART SUPPLY CHAIN -->
                    <div class="section-header">Smart Supply Chain</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fa-solid fa-chart-pie"></i> Dashboard
                            </a>
                        </li>
                    </ul>

                    <!-- SECTION 2: OPERATIONS (Warehouse Staff & Admin & Management) -->
                    @if(Auth::check() && (Auth::user()->isWarehouseStaff() || Auth::user()->isManagement()))
                    <div class="section-header">Operations</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('warehouse.*') ? 'active' : '' }}" href="{{ route('warehouse.index') }}">
                                <i class="fa-solid fa-warehouse"></i> Warehouses & Locations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}" href="{{ route('inventory.index') }}">
                                <i class="fa-solid fa-boxes-stacked"></i> Inventory Balances
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('inventory.transactions') ? 'active' : '' }}" href="{{ route('inventory.transactions') }}">
                                <i class="fa-solid fa-clock-rotate-left"></i> Stock Movements
                            </a>
                        </li>
                    </ul>
                    @endif

                    <!-- SECTION 3: PRODUCTS (Catalog) -->
                    <div class="section-header">Products</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="fa-solid fa-box"></i> Product Catalog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                                <i class="fa-solid fa-tags"></i> Categories
                            </a>
                        </li>
                    </ul>

                    <!-- SECTION 4: PROCUREMENT (Procurement Staff & Management & Admin) -->
                    @if(Auth::check() && (Auth::user()->isProcurementStaff() || Auth::user()->isManagement()))
                    <div class="section-header">Procurement</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                                <i class="fa-solid fa-truck-field"></i> Suppliers & Vendors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('procurement.*') ? 'active' : '' }}" href="{{ route('procurement.index') }}">
                                <i class="fa-solid fa-clipboard-list"></i> Purchase Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                                <i class="fa-solid fa-file-invoice-dollar"></i> Purchase Orders
                            </a>
                        </li>
                    </ul>
                    @endif

                    <!-- SECTION 5: DOCUMENTS -->
                    <div class="section-header">Documents</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
                                <i class="fa-solid fa-folder-closed"></i> Document Tracking
                            </a>
                        </li>
                    </ul>

                    <!-- SECTION 6: INTELLIGENCE (Management & Admin) -->
                    @if(Auth::check() && Auth::user()->isManagement())
                    <div class="section-header">Intelligence</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fa-solid fa-brain" style="color: var(--teal-smart);"></i> AI Forecasting
                            </a>
                        </li>
                    </ul>
                    @endif

                    <!-- SECTION 7: ADMINISTRATION (Admin Only) -->
                    @if(Auth::check() && Auth::user()->isAdmin())
                    <div class="section-header">Administration</div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fa-solid fa-users-gear"></i> User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}" href="{{ route('admin.audit-logs.index') }}">
                                <i class="fa-solid fa-shield-halved"></i> Audit Trail
                            </a>
                        </li>
                    </ul>
                    @endif
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 py-4" id="mainContent">
                <!-- Session Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-info me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Operation Error:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarMenu = document.getElementById('sidebarMenu');
            const mainContent = document.getElementById('mainContent');

            if (sidebarToggle && sidebarMenu) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.innerWidth < 768) {
                        sidebarMenu.classList.toggle('show');
                    } else {
                        sidebarMenu.classList.toggle('collapsed-desktop');
                        if (mainContent) {
                            mainContent.classList.toggle('main-expanded');
                        }
                    }
                });
            }

            // 2. Global Light / Dark Theme System
            window.applyGlobalTheme = function(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);

                const themeToggleBtn = document.getElementById('themeToggleBtn');
                const themeToggleIcon = document.getElementById('themeToggleIcon');
                const themeToggleText = document.getElementById('themeToggleText');

                if (theme === 'dark') {
                    if (themeToggleIcon) themeToggleIcon.className = 'fa-solid fa-sun text-warning me-1';
                    if (themeToggleText) themeToggleText.textContent = 'Light Mode';
                    if (themeToggleBtn) themeToggleBtn.title = 'Switch to Light Mode';
                } else {
                    if (themeToggleIcon) themeToggleIcon.className = 'fa-solid fa-moon text-light me-1';
                    if (themeToggleText) themeToggleText.textContent = 'Dark Mode';
                    if (themeToggleBtn) themeToggleBtn.title = 'Switch to Dark Mode';
                }

                if (window.updateDashboardChartsTheme) {
                    window.updateDashboardChartsTheme(theme);
                }
                if (window.updateReportsChartsTheme) {
                    window.updateReportsChartsTheme(theme);
                }
            };

            const currentTheme = document.documentElement.getAttribute('data-theme') || localStorage.getItem('theme') || 'light';
            window.applyGlobalTheme(currentTheme);

            const themeToggleBtn = document.getElementById('themeToggleBtn');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const activeTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                    const newTheme = activeTheme === 'dark' ? 'light' : 'dark';
                    window.applyGlobalTheme(newTheme);
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
