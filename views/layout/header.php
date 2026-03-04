<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?></title>

    <!-- Bootstrap 5.3 CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts : Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        /* ====================================================
           PALETTE COULEURS
           Primary   : #1E293B  bleu ardoise foncé
           Secondary : #0F172A  presque noir
           Accent    : #2563EB  bleu électrique
           Success   : #16A34A
           Danger    : #DC2626
        ==================================================== */

        :root {
            --color-primary   : #1E293B;
            --color-secondary : #0F172A;
            --color-accent    : #2563EB;
            --color-success   : #16A34A;
            --color-danger    : #DC2626;
            --color-bg        : #F1F5F9;
            --color-surface   : #FFFFFF;
            --color-border    : #E2E8F0;
            --color-text      : #1E293B;
            --color-muted     : #64748B;
            --sidebar-width   : 260px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg);
            color: var(--color-text);
            font-size: 0.875rem;
            min-height: 100vh;
        }

        /* ---- Sidebar ---- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--color-secondary);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-brand .brand-name {
            color: #FFFFFF;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.02em;
            line-height: 1.3;
        }

        .sidebar-brand .brand-sub {
            color: #94A3B8;
            font-size: 0.7rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar-logo {
            width: 36px;
            height: 36px;
            background: var(--color-accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
        }

        .nav-section-label {
            color: #475569;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.5rem 1.25rem 0.25rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.625rem 1.25rem;
            color: #94A3B8;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
            border-radius: 0;
            transition: color 0.15s, background 0.15s;
            border-left: 3px solid transparent;
        }

        .nav-item-link:hover {
            color: #FFFFFF;
            background: rgba(255,255,255,0.05);
        }

        .nav-item-link.active {
            color: #FFFFFF;
            background: rgba(37,99,235,0.15);
            border-left-color: var(--color-accent);
        }

        .nav-item-link .bi {
            font-size: 1rem;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--color-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .user-name {
            color: #FFFFFF;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .user-role {
            color: #64748B;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ---- Main content ---- */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---- Top header bar ---- */
        .topbar {
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            padding: 0.875rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--color-text);
        }

        .topbar-meta {
            font-size: 0.75rem;
            color: var(--color-muted);
        }

        /* ---- Page content ---- */
        .page-content {
            flex: 1;
            padding: 1.75rem;
        }

        /* ---- Cards ---- */
        .card {
            border: 1px solid var(--color-border);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .card-header {
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            padding: 1rem 1.25rem;
            border-radius: 10px 10px 0 0 !important;
        }

        .card-header-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text);
        }

        /* ---- Stat cards ---- */
        .stat-card {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: 10px;
            padding: 1.25rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--color-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        /* ---- Tables ---- */
        .table {
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .table thead th {
            background: #F8FAFC;
            color: var(--color-muted);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-bottom: 1px solid var(--color-border);
            padding: 0.6rem 0.875rem;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 0.7rem 0.875rem;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
            color: var(--color-text);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover td {
            background: #F8FAFC;
        }

        /* ---- Badges d'action ---- */
        .badge-insert {
            background: rgba(22,163,74,0.1);
            color: var(--color-success);
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-update {
            background: rgba(37,99,235,0.1);
            color: var(--color-accent);
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-delete {
            background: rgba(220,38,38,0.1);
            color: var(--color-danger);
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ---- Boutons ---- */
        .btn-primary-custom {
            background: var(--color-accent);
            border-color: var(--color-accent);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .btn-primary-custom:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: #fff;
        }

        .btn-icon {
            padding: 0.3rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1;
        }

        /* ---- Formulaires ---- */
        .form-label {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--color-text);
            margin-bottom: 0.3rem;
        }

        .form-control, .form-select {
            font-size: 0.8rem;
            border-color: var(--color-border);
            border-radius: 6px;
            padding: 0.45rem 0.75rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }

        /* ---- Pagination ---- */
        .pagination .page-link {
            font-size: 0.78rem;
            color: var(--color-accent);
            border-color: var(--color-border);
            padding: 0.35rem 0.65rem;
        }

        .pagination .page-item.active .page-link {
            background: var(--color-accent);
            border-color: var(--color-accent);
            color: #fff;
        }

        .pagination .page-link:hover {
            color: var(--color-accent);
            background: #EFF6FF;
        }

        /* ---- Flash messages ---- */
        .flash-message {
            font-size: 0.8rem;
            border-radius: 6px;
        }

        /* ---- Modals ---- */
        .modal-header {
            background: var(--color-primary);
            color: #fff;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
            padding: 1rem 1.25rem;
        }

        .modal-header .modal-title {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .modal-footer {
            border-top: 1px solid var(--color-border);
            padding: 0.75rem 1.25rem;
        }

        .modal-body {
            padding: 1.25rem;
        }

        /* ---- Responsive ---- */
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-wrapper { margin-left: 0; }
        }
    </style>
</head>
<body>
