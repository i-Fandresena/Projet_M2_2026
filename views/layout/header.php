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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        /* ====================================================
           SUPERVISION BANCAIRE — Design System "Céleste"
           Palette: violet #8b5cf6 + teal #2dd4bf
        ==================================================== */
        :root {
            --bg        : #09071a;
            --surface   : #0f0c22;
            --elevated  : #150f2c;
            --card      : #120e27;
            --border    : rgba(139,92,246,.1);
            --border-hi : rgba(139,92,246,.22);
            --accent    : #8b5cf6;
            --accent2   : #2dd4bf;
            --success   : #34d399;
            --warning   : #fbbf24;
            --danger    : #fb7185;
            --text      : #ede9fe;
            --text-2    : #9d9abc;
            --text-3    : #514e6a;
            --sidebar-w : 252px;
            --r         : 10px;
            --r-sm      : 6px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.125rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #7c3aed, #2dd4bf);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(124,58,237,.38);
        }

        .brand-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 0.6rem;
            color: var(--text-3);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.625rem;
        }

        .nav-section-label {
            font-size: 0.59rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-3);
            padding: 0.5rem 0.5rem 0.4rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.625rem 0.875rem;
            margin-bottom: 2px;
            color: var(--text-2);
            text-decoration: none;
            font-size: 0.81rem;
            font-weight: 500;
            border-radius: var(--r-sm);
            transition: background .15s, color .15s, transform .15s;
            position: relative;
        }

        .nav-item-link .bi { font-size: 1rem; flex-shrink: 0; }

        .nav-item-link:hover {
            background: rgba(139,92,246,.08);
            color: var(--text);
            transform: translateX(2px);
        }

        .nav-item-link.active {
            background: rgba(139,92,246,.14);
            color: #c4b5fd;
            font-weight: 600;
        }

        .nav-item-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 22%; bottom: 22%;
            width: 3px;
            background: linear-gradient(180deg, #8b5cf6, #2dd4bf);
            border-radius: 0 3px 3px 0;
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(139,92,246,.14);
            color: #a78bfa;
            font-size: 0.58rem;
            font-weight: 700;
            padding: 0.12rem 0.4rem;
            border-radius: 100px;
        }

        .sidebar-footer {
            padding: 0.875rem;
            border-top: 1px solid var(--border);
        }

        .user-card {
            background: rgba(139,92,246,.05);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            padding: 0.625rem 0.75rem;
            margin-bottom: 0.625rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .user-avatar {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, #7c3aed, #2dd4bf);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.73rem;
            flex-shrink: 0;
        }

        .user-name  { color: var(--text); font-weight: 600; font-size: 0.78rem; line-height: 1.2; }
        .user-role  {
            color: var(--text-3);
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .btn-logout {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            background: rgba(251,113,133,.06);
            border: 1px solid rgba(251,113,133,.14);
            border-radius: var(--r-sm);
            color: #fda4af;
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.5rem;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-logout:hover {
            background: rgba(251,113,133,.12);
            border-color: rgba(251,113,133,.28);
            color: #fecdd3;
        }

        /* ── MAIN WRAPPER ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            background: rgba(9,7,26,.85);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            height: 58px;
            padding: 0 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
        }

        .topbar-title {
            display: flex; align-items: center; gap: 0.625rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
        }

        .title-icon {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, rgba(139,92,246,.18), rgba(45,212,191,.1));
            border: 1px solid rgba(139,92,246,.2);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.78rem;
            color: #a78bfa;
        }

        .topbar-meta {
            font-size: 0.7rem;
            color: var(--text-3);
            font-weight: 400;
            margin-top: 1px;
        }

        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }

        .topbar-chip {
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            border-radius: 100px;
            padding: 0.27rem 0.75rem;
            font-size: 0.69rem;
            color: var(--text-3);
            display: flex; align-items: center; gap: 0.35rem;
        }

        /* ── PAGE CONTENT ── */
        .page-content { flex: 1; padding: 1.5rem 1.75rem; }

        /* ── STAT GRID ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 1.125rem;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
            cursor: default;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,.45);
        }

        .stat-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 1px;
        }

        .stat-card.accent1::before { background: linear-gradient(90deg, #8b5cf6, transparent 70%); }
        .stat-card.accent2::before { background: linear-gradient(90deg, #2dd4bf, transparent 70%); }
        .stat-card.accent3::before { background: linear-gradient(90deg, #fbbf24, transparent 70%); }
        .stat-card.accent4::before { background: linear-gradient(90deg, #fb7185, transparent 70%); }

        .stat-top {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 0.875rem;
        }

        .stat-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem;
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .stat-label {
            font-size: 0.67rem;
            color: var(--text-3);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.2rem;
        }

        /* ── MAIN CARD ── */
        .main-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            overflow: hidden;
        }

        .main-card-header {
            padding: 0.875rem 1.125rem;
            background: rgba(255,255,255,.015);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 0.75rem;
        }

        .main-card-title {
            font-size: 0.81rem;
            font-weight: 600;
            color: var(--text);
            display: flex; align-items: center; gap: 0.5rem;
        }

        /* ── DATA TABLE ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.79rem;
        }

        .data-table thead th {
            padding: 0.6rem 1rem;
            color: var(--text-3);
            font-size: 0.63rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,.018);
        }

        .data-table tbody td {
            padding: 0.7rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(139,92,246,.05);
            color: var(--text);
            transition: background .1s, padding-left .1s;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }

        .data-table tbody tr:hover td {
            background: rgba(139,92,246,.046);
            padding-left: 1.125rem;
        }

        .data-table tbody tr:hover td:first-child {
            border-left: 2px solid rgba(139,92,246,.38);
            padding-left: 0.875rem;
        }

        /* ── BADGES ── */
        .badge-action {
            display: inline-flex; align-items: center;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            padding: 0.22rem 0.55rem;
            border-radius: 4px;
        }

        .badge-insert { background: rgba(45,212,191,.1);  color: #2dd4bf; border: 1px solid rgba(45,212,191,.2); }
        .badge-update { background: rgba(139,92,246,.1);  color: #a78bfa; border: 1px solid rgba(139,92,246,.2); }
        .badge-delete { background: rgba(251,113,133,.09); color: #fb7185; border: 1px solid rgba(251,113,133,.2); }

        /* ── BUTTONS ── */
        .btn-primary-custom {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: linear-gradient(135deg, #7c3aed, #4338ca);
            border: none;
            border-radius: var(--r-sm);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.77rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: all .18s;
            box-shadow: 0 2px 10px rgba(124,58,237,.28);
        }

        .btn-primary-custom:hover {
            box-shadow: 0 4px 18px rgba(124,58,237,.42);
            transform: translateY(-1px);
        }

        .btn-icon-action {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px;
            border-radius: var(--r-sm);
            border: none;
            cursor: pointer;
            transition: all .15s;
            font-size: 0.78rem;
        }

        .btn-icon-action.edit { background: rgba(139,92,246,.1); color: #a78bfa; }
        .btn-icon-action.edit:hover { background: rgba(139,92,246,.22); color: #c4b5fd; }
        .btn-icon-action.del  { background: rgba(251,113,133,.08); color: #fb7185; }
        .btn-icon-action.del:hover  { background: rgba(251,113,133,.18); color: #fda4af; }

        /* ── FORM CONTROLS ── */
        .form-label {
            font-size: 0.74rem;
            font-weight: 500;
            color: var(--text-2);
            margin-bottom: 0.3rem;
        }

        .form-control, .form-select {
            background: var(--elevated);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus, .form-select:focus {
            background: var(--elevated);
            border-color: rgba(139,92,246,.42);
            box-shadow: 0 0 0 3px rgba(139,92,246,.1);
            color: var(--text);
            outline: none;
        }

        .form-control::placeholder { color: var(--text-3); }
        .form-select option { background: var(--elevated); color: var(--text); }
        .input-with-icon { position: relative; }
        .input-with-icon .input-icon {
            position: absolute; left: 0.75rem; top: 50%;
            transform: translateY(-50%);
            color: var(--text-3); font-size: 0.85rem; pointer-events: none;
        }
        .input-with-icon .form-control { padding-left: 2.5rem; }

        /* ── SEARCH BAR ── */
        .search-bar {
            background: var(--elevated);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            display: flex; align-items: center;
            padding: 0 0.75rem; gap: 0.5rem;
            min-width: 210px;
            transition: border-color .15s;
        }

        .search-bar:focus-within {
            border-color: rgba(139,92,246,.35);
            box-shadow: 0 0 0 3px rgba(139,92,246,.07);
        }

        .search-bar .bi-search { color: var(--text-3); font-size: 0.77rem; }

        .search-bar input {
            background: none; border: none; outline: none;
            color: var(--text); font-family: 'Inter', sans-serif;
            font-size: 0.79rem; padding: 0.5rem 0; width: 100%;
        }

        .search-bar input::placeholder { color: var(--text-3); }

        .btn-filter {
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            color: var(--text-2);
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem; font-weight: 500;
            padding: 0.5rem 0.875rem;
            cursor: pointer; transition: all .15s;
        }

        .btn-filter:hover {
            background: rgba(139,92,246,.09);
            border-color: var(--border-hi);
            color: #c4b5fd;
        }

        .btn-clear {
            background: none;
            border: 1px solid rgba(251,113,133,.16);
            border-radius: var(--r-sm);
            color: #fb7185; font-size: 0.75rem;
            padding: 0.5rem 0.6rem;
            cursor: pointer; transition: all .15s;
        }

        .btn-clear:hover { background: rgba(251,113,133,.08); }

        /* ── PAGINATION ── */
        .pager { display: flex; align-items: center; gap: 0.2rem; }

        .pager-btn {
            min-width: 30px; height: 30px;
            padding: 0 0.5rem;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--text-2);
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            display: inline-flex; align-items: center; justify-content: center;
            text-decoration: none; cursor: pointer;
            transition: all .15s;
        }

        .pager-btn:hover:not(.active):not([aria-disabled="true"]) {
            background: rgba(139,92,246,.1);
            border-color: var(--border-hi);
            color: #c4b5fd;
        }

        .pager-btn.active {
            background: linear-gradient(135deg, #7c3aed, #4338ca);
            border-color: transparent;
            color: #fff; font-weight: 600;
        }

        .pager-btn[aria-disabled="true"], .pager-btn.disabled {
            opacity: 0.3; cursor: not-allowed; pointer-events: none;
        }

        /* ── FLASH MESSAGES ── */
        .flash-success {
            background: rgba(52,211,153,.07);
            border: 1px solid rgba(52,211,153,.18);
            border-radius: var(--r-sm);
            color: #6ee7b7; font-size: 0.8rem;
            padding: 0.7rem 1rem;
            display: flex; align-items: center; gap: 0.6rem;
            margin-bottom: 1rem;
            animation: fadeSlide .25s ease;
        }

        .flash-error {
            background: rgba(251,113,133,.07);
            border: 1px solid rgba(251,113,133,.18);
            border-radius: var(--r-sm);
            color: #fda4af; font-size: 0.8rem;
            padding: 0.7rem 1rem;
            display: flex; align-items: center; gap: 0.6rem;
            margin-bottom: 1rem;
            animation: fadeSlide .25s ease;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(-5px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── MODALS ── */
        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border-hi);
            border-radius: 12px !important;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,.65);
        }

        .modal-header {
            background: var(--elevated);
            border-bottom: 1px solid var(--border);
            padding: 0.875rem 1.125rem;
        }

        .modal-title {
            color: var(--text); font-size: 0.87rem; font-weight: 600;
            display: flex; align-items: center; gap: 0.5rem;
        }

        .modal-header .btn-close { filter: invert(1) opacity(.38); }
        .modal-header .btn-close:hover { filter: invert(1) opacity(.7); }

        .modal-body { padding: 1.375rem; background: var(--surface); }

        .modal-footer {
            background: var(--elevated);
            border-top: 1px solid var(--border);
            padding: 0.875rem 1.125rem; gap: 0.5rem;
        }

        .btn-modal-cancel {
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            color: var(--text-2);
            font-family: 'Inter', sans-serif;
            font-size: 0.79rem; font-weight: 500;
            padding: 0.5rem 1rem; cursor: pointer; transition: all .15s;
        }

        .btn-modal-cancel:hover { border-color: var(--border-hi); color: var(--text); }

        .btn-modal-save {
            background: linear-gradient(135deg, #7c3aed, #4338ca);
            border: none;
            border-radius: var(--r-sm);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.79rem; font-weight: 600;
            padding: 0.5rem 1.25rem; cursor: pointer;
            transition: all .18s;
            box-shadow: 0 2px 10px rgba(124,58,237,.28);
        }

        .btn-modal-save:hover {
            box-shadow: 0 4px 18px rgba(124,58,237,.42);
            transform: translateY(-1px);
        }

        .btn-modal-danger {
            background: rgba(251,113,133,.09);
            border: 1px solid rgba(251,113,133,.2);
            border-radius: var(--r-sm);
            color: #fb7185;
            font-family: 'Inter', sans-serif;
            font-size: 0.79rem; font-weight: 600;
            padding: 0.5rem 1.25rem; cursor: pointer; transition: all .18s;
        }

        .btn-modal-danger:hover {
            background: rgba(251,113,133,.18);
            border-color: rgba(251,113,133,.36);
        }

        .delete-confirm-box {
            background: rgba(251,113,133,.06);
            border: 1px solid rgba(251,113,133,.13);
            border-radius: var(--r-sm);
            padding: 1.25rem; text-align: center;
            color: #fda4af; font-size: 0.82rem;
        }

        .delete-confirm-box .di { font-size: 2.25rem; margin-bottom: 0.625rem; opacity: .6; }

        /* ── TABLE FOOTER ── */
        .table-footer {
            padding: 0.75rem 1.125rem;
            background: rgba(255,255,255,.012);
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 0.5rem;
        }

        .table-footer-meta { font-size: 0.69rem; color: var(--text-3); }

        /* ── FILTER SELECT ── */
        .filter-select {
            background: var(--elevated);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.76rem;
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            cursor: pointer; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23514e6a' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
            outline: none; transition: border-color .15s;
        }

        .filter-select:focus { border-color: rgba(139,92,246,.35); }
        .filter-select option { background: #150f2c; }

        /* ── AMOUNTS ── */
        .amount-positive { color: #34d399; font-weight: 600; }
        .amount-neutral  { color: var(--text-3); }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(139,92,246,.22); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(139,92,246,.38); }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; }
            .main-wrapper { margin-left: 0; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .page-content { padding: 1rem; }
        }
    </style>
</head>
<body>
