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
           DESIGN SYSTEM â€” Supervision Bancaire v2
        ==================================================== */
        :root {
            --c-bg        : #070d1a;
            --c-surface   : #0d1628;
            --c-elevated  : #111e35;
            --c-card      : #0f1c32;
            --c-border    : rgba(255,255,255,0.07);
            --c-accent    : #3b82f6;
            --c-accent2   : #6366f1;
            --c-success   : #10b981;
            --c-warning   : #f59e0b;
            --c-danger    : #ef4444;
            --c-text      : #e2e8f0;
            --c-muted     : #475569;
            --c-muted2    : #64748b;
            --sidebar-w   : 260px;
            --radius      : 12px;
            --radius-sm   : 8px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            background: var(--c-bg);
            color: var(--c-text);
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SIDEBAR â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, #0b1424 0%, #070d1a 100%);
            border-right: 1px solid var(--c-border);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 1px; height: 100%;
            background: linear-gradient(180deg, transparent, rgba(59,130,246,0.3), rgba(99,102,241,0.2), transparent);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--c-border);
        }

        .sidebar-logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff;
            box-shadow: 0 0 20px rgba(37,99,235,0.3);
            flex-shrink: 0;
        }

        .brand-name {
            color: #f1f5f9;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
        }

        .brand-sub {
            color: var(--c-muted);
            font-size: 0.65rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 1px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0.75rem;
        }

        .nav-section-label {
            color: var(--c-muted);
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            padding: 0 0.5rem 0.6rem;
            margin-bottom: 0.25rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.875rem;
            margin-bottom: 2px;
            color: var(--c-muted2);
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            border-radius: var(--radius-sm);
            transition: all 0.18s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-item-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(59,130,246,0.08), rgba(99,102,241,0.06));
            border-radius: var(--radius-sm);
            opacity: 0;
            transition: opacity 0.18s;
        }

        .nav-item-link:hover {
            color: #c7d2fe;
            transform: translateX(2px);
        }
        .nav-item-link:hover::before { opacity: 1; }

        .nav-item-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(99,102,241,0.1));
            border: 1px solid rgba(59,130,246,0.25);
        }

        .nav-item-link.active::after {
            content: '';
            position: absolute;
            left: 0; top: 25%; bottom: 25%;
            width: 3px;
            background: linear-gradient(180deg, #3b82f6, #6366f1);
            border-radius: 0 4px 4px 0;
        }

        .nav-item-link .bi {
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(59,130,246,0.15);
            color: #60a5fa;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 100px;
        }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--c-border);
            margin-top: auto;
        }

        .user-card {
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .user-name { color: #e2e8f0; font-weight: 600; font-size: 0.8rem; }
        .user-role {
            color: var(--c-muted);
            font-size: 0.65rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            background: rgba(239,68,68,0.07);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: var(--radius-sm);
            color: #fca5a5;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 500;
            padding: 0.55rem 0.875rem;
            cursor: pointer;
            transition: all 0.18s;
        }

        .btn-logout:hover {
            background: rgba(239,68,68,0.15);
            border-color: rgba(239,68,68,0.4);
            color: #fecaca;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ MAIN WRAPPER â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--c-bg);
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ TOPBAR â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .topbar {
            background: var(--c-surface);
            border-bottom: 1px solid var(--c-border);
            padding: 0 2rem;
            height: 60px;
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
            color: var(--c-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar-title .title-icon {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(99,102,241,0.15));
            border: 1px solid rgba(59,130,246,0.2);
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
            color: #60a5fa;
        }

        .topbar-meta {
            font-size: 0.75rem;
            color: var(--c-muted);
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-chip {
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: 100px;
            padding: 0.3rem 0.875rem;
            font-size: 0.73rem;
            color: var(--c-muted2);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ PAGE CONTENT â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .page-content {
            flex: 1;
            padding: 1.75rem 2rem;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ STAT CARDS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--c-card);
            border: 1px solid var(--c-border);
            border-radius: var(--radius);
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.18s, box-shadow 0.18s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
        }

        .stat-card.accent1::before { background: linear-gradient(90deg, #3b82f6, #6366f1); }
        .stat-card.accent2::before { background: linear-gradient(90deg, #10b981, #06b6d4); }
        .stat-card.accent3::before { background: linear-gradient(90deg, #f59e0b, #f97316); }
        .stat-card.accent4::before { background: linear-gradient(90deg, #ef4444, #ec4899); }

        .stat-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            color: #f1f5f9;
        }

        .stat-label {
            font-size: 0.72rem;
            color: var(--c-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 0.3rem;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ MAIN CARD â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .main-card {
            background: var(--c-card);
            border: 1px solid var(--c-border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .main-card-header {
            padding: 1rem 1.25rem;
            background: var(--c-elevated);
            border-bottom: 1px solid var(--c-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .main-card-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--c-text);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ TABLE â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .data-table thead tr {
            background: rgba(255,255,255,0.02);
        }

        .data-table thead th {
            padding: 0.65rem 1rem;
            color: var(--c-muted);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-bottom: 1px solid var(--c-border);
            white-space: nowrap;
        }

        .data-table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.03);
            transition: background 0.15s;
        }

        .data-table tbody tr:hover {
            background: rgba(59,130,246,0.04);
        }

        .data-table tbody tr:last-child { border-bottom: none; }

        .data-table tbody td {
            padding: 0.8rem 1rem;
            vertical-align: middle;
            color: var(--c-text);
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--c-muted);
        }
        .empty-state .empty-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            opacity: 0.4;
        }
        .empty-state p { font-size: 0.85rem; }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ BADGES â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .badge-action {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .badge-insert { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
        .badge-update { background: rgba(59,130,246,0.12); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2); }
        .badge-delete { background: rgba(239,68,68,0.12);  color: #f87171; border: 1px solid rgba(239,68,68,0.2); }

        .cheque-tag {
            font-family: 'SF Mono', 'Fira Code', monospace;
            background: rgba(59,130,246,0.08);
            border: 1px solid rgba(59,130,246,0.15);
            color: #93c5fd;
            font-size: 0.72rem;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ BTNS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .btn-primary-custom {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border: none;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.18s;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.35);
            color: #fff;
        }

        .btn-icon-action {
            width: 30px; height: 30px;
            border-radius: 7px;
            border: 1px solid var(--c-border);
            background: var(--c-elevated);
            color: var(--c-muted2);
            font-size: 0.78rem;
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-icon-action.edit:hover {
            background: rgba(59,130,246,0.12);
            border-color: rgba(59,130,246,0.3);
            color: #60a5fa;
        }

        .btn-icon-action.del:hover {
            background: rgba(239,68,68,0.12);
            border-color: rgba(239,68,68,0.3);
            color: #f87171;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ FORMS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .form-label {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--c-muted2);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 0.45rem;
            display: block;
        }

        .form-control, .form-select {
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            color: var(--c-text);
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            padding: 0.6rem 0.875rem;
            transition: border-color 0.18s, box-shadow 0.18s;
            width: 100%;
        }

        .form-control::placeholder { color: var(--c-muted); }
        .form-select option { background: #111e35; }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: rgba(59,130,246,0.5);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
            background: var(--c-card);
            color: var(--c-text);
        }

        .input-with-icon {
            position: relative;
        }
        .input-with-icon .input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--c-muted);
            font-size: 0.9rem;
            pointer-events: none;
        }
        .input-with-icon .form-control { padding-left: 2.5rem; }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SEARCH BAR â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .search-bar {
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
            gap: 0.5rem;
            min-width: 220px;
            transition: border-color 0.18s;
        }

        .search-bar:focus-within {
            border-color: rgba(59,130,246,0.4);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.07);
        }

        .search-bar .bi-search { color: var(--c-muted); font-size: 0.8rem; }

        .search-bar input {
            background: none;
            border: none;
            outline: none;
            color: var(--c-text);
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            padding: 0.5rem 0;
            width: 100%;
        }
        .search-bar input::placeholder { color: var(--c-muted); }

        .btn-filter {
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            color: var(--c-muted2);
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 500;
            padding: 0.5rem 0.875rem;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-filter:hover {
            border-color: rgba(59,130,246,0.3);
            color: #60a5fa;
            background: rgba(59,130,246,0.07);
        }

        .btn-clear {
            background: none;
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: var(--radius-sm);
            color: #f87171;
            font-size: 0.78rem;
            padding: 0.5rem 0.6rem;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-clear:hover { background: rgba(239,68,68,0.08); }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ PAGINATION â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .pager {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .pager-btn {
            min-width: 30px; height: 30px;
            padding: 0 0.5rem;
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: 6px;
            color: var(--c-muted2);
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .pager-btn:hover:not(.active):not([aria-disabled="true"]) {
            border-color: rgba(59,130,246,0.3);
            color: #60a5fa;
            background: rgba(59,130,246,0.07);
        }

        .pager-btn.active {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-color: transparent;
            color: #fff;
            font-weight: 600;
        }

        .pager-btn[aria-disabled="true"], .pager-btn.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ FLASH â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .flash-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: var(--radius-sm);
            color: #6ee7b7;
            font-size: 0.82rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
            animation: slideDown 0.3s ease;
        }

        .flash-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.25);
            border-radius: var(--radius-sm);
            color: #fca5a5;
            font-size: 0.82rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ MODALS â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .modal-content {
            background: #0d1628;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px !important;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.6);
        }

        .modal-header {
            background: var(--c-elevated);
            border-bottom: 1px solid var(--c-border);
            padding: 1rem 1.25rem;
        }

        .modal-title {
            color: #f1f5f9;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-header .btn-close {
            filter: invert(1) opacity(0.5);
            transition: opacity 0.15s;
        }
        .modal-header .btn-close:hover { opacity: 1; }

        .modal-body {
            padding: 1.5rem;
            background: #0d1628;
        }

        .modal-footer {
            background: var(--c-elevated);
            border-top: 1px solid var(--c-border);
            padding: 0.875rem 1.25rem;
            gap: 0.5rem;
        }

        .btn-modal-cancel {
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            color: var(--c-muted2);
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            padding: 0.55rem 1rem;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-modal-cancel:hover { border-color: rgba(255,255,255,0.15); color: var(--c-text); }

        .btn-modal-save {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border: none;
            border-radius: var(--radius-sm);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.55rem 1.25rem;
            cursor: pointer;
            transition: all 0.18s;
        }
        .btn-modal-save:hover {
            box-shadow: 0 4px 15px rgba(37,99,235,0.4);
            transform: translateY(-1px);
        }

        .btn-modal-danger {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.3);
            border-radius: var(--radius-sm);
            color: #f87171;
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.55rem 1.25rem;
            cursor: pointer;
            transition: all 0.18s;
        }
        .btn-modal-danger:hover {
            background: rgba(239,68,68,0.2);
            border-color: rgba(239,68,68,0.5);
        }

        /* Delete confirm box */
        .delete-confirm-box {
            background: rgba(239,68,68,0.07);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: var(--radius-sm);
            padding: 1rem;
            text-align: center;
            color: #fca5a5;
            font-size: 0.85rem;
        }

        .delete-confirm-box .di { font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.7; }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ CARD FOOTER TABLE â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .table-footer {
            padding: 0.875rem 1rem;
            background: rgba(255,255,255,0.02);
            border-top: 1px solid var(--c-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .table-footer-meta { font-size: 0.73rem; color: var(--c-muted); }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SELECT FILTER â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .filter-select {
            background: var(--c-elevated);
            border: 1px solid var(--c-border);
            border-radius: var(--radius-sm);
            color: var(--c-text);
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            padding: 0.5rem 2rem 0.5rem 0.875rem;
            cursor: pointer;
            transition: border-color 0.15s;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
            outline: none;
        }
        .filter-select:focus { border-color: rgba(59,130,246,0.4); }
        .filter-select option { background: #111e35; }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ AMOUNT â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .amount-positive { color: #34d399; font-weight: 600; }
        .amount-neutral  { color: var(--c-muted2); }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ SCROLLBAR â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ RESPONSIVE â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-wrapper { margin-left: 0; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .page-content { padding: 1rem; }
        }
    </style>
</head>
<body>
