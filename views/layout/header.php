<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?></title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --bg: #eff2f7;
            --bg-2: #e8edf6;
            --surface: rgba(255, 255, 255, 0.84);
            --surface-strong: #ffffff;
            --card: rgba(255, 255, 255, 0.88);
            --border: rgba(23, 41, 83, 0.12);
            --border-strong: rgba(23, 41, 83, 0.2);
            --accent: #3559e0;
            --accent-2: #06b6d4;
            --success: #0f766e;
            --warning: #b45309;
            --danger: #b91c1c;
            --text: #0f172a;
            --text-2: #334155;
            --text-3: #64748b;
            --sidebar-w: 268px;
            --r: 14px;
            --r-sm: 10px;
            --shadow-soft: 0 12px 30px rgba(15, 23, 42, 0.07);
            --shadow-card: 0 20px 45px rgba(15, 23, 42, 0.08);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text);
            background:
                radial-gradient(circle at 12% 18%, rgba(53,89,224,0.12), transparent 34%),
                radial-gradient(circle at 82% 14%, rgba(6,182,212,0.09), transparent 30%),
                linear-gradient(180deg, var(--bg), var(--bg-2));
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--surface);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-right: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
            z-index: 1000;
            animation: sidebarIn .45s ease;
        }

        @keyframes sidebarIn {
            from { opacity: 0; transform: translateX(-14px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .sidebar-brand {
            padding: 1.1rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(140deg, var(--accent), var(--accent-2));
            box-shadow: 0 10px 18px rgba(53, 89, 224, 0.28);
        }

        .brand-name {
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .brand-sub {
            color: var(--text-3);
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0.72rem;
        }

        .nav-section-label {
            font-size: 0.61rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-3);
            padding: 0.45rem 0.6rem 0.55rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.7rem 0.85rem;
            margin-bottom: 2px;
            text-decoration: none;
            color: var(--text-2);
            border-radius: var(--r-sm);
            font-size: 0.83rem;
            font-weight: 500;
            transition: all .18s ease;
            position: relative;
        }

        .nav-item-link .bi { font-size: 1rem; }

        .nav-item-link:hover {
            transform: translateX(3px);
            background: rgba(53, 89, 224, 0.08);
            color: #1d4ed8;
        }

        .nav-item-link.active {
            color: #1e3a8a;
            background: linear-gradient(120deg, rgba(53,89,224,0.16), rgba(6,182,212,0.1));
            border: 1px solid rgba(53, 89, 224, 0.22);
            font-weight: 600;
        }

        .nav-item-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 24%;
            bottom: 24%;
            width: 3px;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(180deg, var(--accent), var(--accent-2));
        }

        .sidebar-footer {
            padding: 0.92rem;
            border-top: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.45);
        }

        .user-card {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            padding: 0.72rem;
            margin-bottom: 0.62rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            background: linear-gradient(140deg, #2563eb, #06b6d4);
        }

        .user-name { color: var(--text); font-size: 0.8rem; font-weight: 600; }
        .user-role {
            color: var(--text-3);
            font-size: 0.63rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.48rem;
            border: 1px solid rgba(185, 28, 28, 0.18);
            border-radius: var(--r-sm);
            background: rgba(185, 28, 28, 0.05);
            color: #b91c1c;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 500;
            padding: 0.56rem;
            transition: all .16s ease;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: rgba(185, 28, 28, 0.1);
            border-color: rgba(185, 28, 28, 0.3);
            transform: translateY(-1px);
        }

        .main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 80;
            height: 62px;
            padding: 0 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--border);
        }

        .topbar-title {
            display: flex;
            align-items: center;
            gap: 0.62rem;
            color: var(--text);
            font-size: 0.95rem;
            font-weight: 700;
        }

        .title-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid rgba(53, 89, 224, 0.24);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            background: rgba(53, 89, 224, 0.08);
        }

        .topbar-meta {
            color: var(--text-3);
            font-size: 0.72rem;
            font-weight: 500;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.72rem;
        }

        .topbar-chip {
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.75);
            border-radius: 999px;
            color: var(--text-3);
            font-size: 0.72rem;
            font-weight: 500;
            padding: 0.3rem 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 0.36rem;
        }

        .page-content {
            flex: 1;
            padding: 1.65rem 1.85rem;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.95rem;
            margin-bottom: 1.3rem;
        }

        .stat-card {
            border: 1px solid var(--border);
            border-radius: var(--r);
            background: var(--card);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 1.14rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-card);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 2px;
        }

        .stat-card.accent1::before { background: linear-gradient(90deg, #3559e0, #0ea5e9); }
        .stat-card.accent2::before { background: linear-gradient(90deg, #0f766e, #14b8a6); }
        .stat-card.accent3::before { background: linear-gradient(90deg, #b45309, #f59e0b); }
        .stat-card.accent4::before { background: linear-gradient(90deg, #b91c1c, #ef4444); }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.92rem;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
        }

        .stat-value {
            color: var(--text);
            font-size: 1.95rem;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .stat-label {
            color: var(--text-3);
            font-size: 0.66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 0.24rem;
        }

        .main-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            animation: panelIn .35s ease;
        }

        @keyframes panelIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .main-card-header {
            padding: 0.92rem 1.16rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.62);
        }

        .main-card-title {
            color: var(--text);
            font-size: 0.83rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .data-table thead th {
            background: rgba(53, 89, 224, 0.05);
            border-bottom: 1px solid var(--border);
            color: #475569;
            font-size: 0.64rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.66rem 1rem;
            white-space: nowrap;
        }

        .data-table tbody td {
            padding: 0.74rem 1rem;
            border-bottom: 1px solid rgba(23, 41, 83, 0.07);
            color: var(--text);
            vertical-align: middle;
            transition: background .16s, transform .16s;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }

        .data-table tbody tr:hover td {
            background: rgba(53, 89, 224, 0.05);
        }

        .badge-action {
            display: inline-flex;
            align-items: center;
            padding: 0.24rem 0.52rem;
            border-radius: 6px;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .badge-insert {
            color: #115e59;
            background: rgba(15, 118, 110, 0.12);
            border: 1px solid rgba(15, 118, 110, 0.2);
        }

        .badge-update {
            color: #1d4ed8;
            background: rgba(37, 99, 235, 0.12);
            border: 1px solid rgba(37, 99, 235, 0.18);
        }

        .badge-delete {
            color: #b91c1c;
            background: rgba(185, 28, 28, 0.1);
            border: 1px solid rgba(185, 28, 28, 0.2);
        }

        .btn-primary-custom {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border: none;
            border-radius: var(--r-sm);
            padding: 0.54rem 1rem;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            background: linear-gradient(135deg, #3559e0, #0ea5e9);
            box-shadow: 0 10px 18px rgba(53, 89, 224, 0.26);
            transition: transform .16s, box-shadow .16s;
            cursor: pointer;
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 25px rgba(53, 89, 224, 0.3);
        }

        .btn-icon-action {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .16s ease;
        }

        .btn-icon-action.edit {
            background: rgba(37, 99, 235, 0.1);
            color: #1d4ed8;
        }

        .btn-icon-action.edit:hover {
            background: rgba(37, 99, 235, 0.2);
            transform: translateY(-1px);
        }

        .btn-icon-action.del {
            background: rgba(185, 28, 28, 0.1);
            color: #b91c1c;
        }

        .btn-icon-action.del:hover {
            background: rgba(185, 28, 28, 0.2);
            transform: translateY(-1px);
        }

        .form-label {
            color: var(--text-2);
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: 0.32rem;
        }

        .form-control,
        .form-select {
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.85);
            color: var(--text);
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
            padding: 0.52rem 0.76rem;
            transition: border-color .16s, box-shadow .16s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(53, 89, 224, 0.35);
            box-shadow: 0 0 0 3px rgba(53, 89, 224, 0.11);
            background: #fff;
            color: var(--text);
            outline: none;
        }

        .form-control::placeholder { color: #94a3b8; }
        .form-select option { background: #fff; color: var(--text); }

        .input-with-icon { position: relative; }

        .input-with-icon .input-icon {
            position: absolute;
            left: 0.76rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.86rem;
            pointer-events: none;
        }

        .input-with-icon .form-control { padding-left: 2.5rem; }

        .search-bar {
            min-width: 230px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid var(--border);
            border-radius: var(--r-sm);
            padding: 0 0.72rem;
            background: rgba(255, 255, 255, 0.8);
            transition: all .16s;
        }

        .search-bar:focus-within {
            border-color: rgba(53, 89, 224, 0.34);
            box-shadow: 0 0 0 3px rgba(53, 89, 224, 0.11);
            background: #fff;
        }

        .search-bar .bi-search { color: #94a3b8; font-size: 0.79rem; }

        .search-bar input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            padding: 0.55rem 0;
        }

        .search-bar input::placeholder { color: #94a3b8; }

        .btn-filter,
        .btn-clear {
            border-radius: var(--r-sm);
            font-family: 'Inter', sans-serif;
            font-size: 0.76rem;
            font-weight: 500;
            transition: all .15s;
            cursor: pointer;
        }

        .btn-filter {
            border: 1px solid var(--border);
            color: var(--text-2);
            background: rgba(255, 255, 255, 0.8);
            padding: 0.52rem 0.9rem;
        }

        .btn-filter:hover {
            border-color: rgba(53, 89, 224, 0.3);
            color: #1e3a8a;
            background: rgba(53, 89, 224, 0.08);
        }

        .btn-clear {
            border: 1px solid rgba(185, 28, 28, 0.22);
            color: #b91c1c;
            background: rgba(185, 28, 28, 0.06);
            padding: 0.52rem 0.62rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-clear:hover {
            background: rgba(185, 28, 28, 0.12);
            color: #991b1b;
        }

        .pager {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .pager-btn {
            min-width: 31px;
            height: 31px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.85);
            color: var(--text-2);
            font-size: 0.76rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }

        .pager-btn:hover:not(.active):not([aria-disabled="true"]) {
            border-color: rgba(53, 89, 224, 0.3);
            color: #1e40af;
            background: rgba(53, 89, 224, 0.08);
        }

        .pager-btn.active {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, #3559e0, #0ea5e9);
            font-weight: 600;
        }

        .pager-btn[aria-disabled="true"], .pager-btn.disabled {
            opacity: 0.38;
            pointer-events: none;
        }

        .flash-success,
        .flash-error {
            border-radius: var(--r-sm);
            padding: 0.74rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.58rem;
            margin-bottom: 1.1rem;
            font-size: 0.81rem;
            animation: flashIn .26s ease;
        }

        .flash-success {
            background: rgba(15, 118, 110, 0.11);
            border: 1px solid rgba(15, 118, 110, 0.22);
            color: #115e59;
        }

        .flash-error {
            background: rgba(185, 28, 28, 0.1);
            border: 1px solid rgba(185, 28, 28, 0.24);
            color: #991b1b;
        }

        @keyframes flashIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-content {
            background: #fff;
            border: 1px solid var(--border-strong);
            border-radius: 14px !important;
            overflow: hidden;
            box-shadow: 0 25px 55px rgba(15, 23, 42, 0.18);
        }

        .modal-header {
            background: rgba(248, 250, 252, 0.95);
            border-bottom: 1px solid var(--border);
            padding: 0.95rem 1.18rem;
        }

        .modal-title {
            color: var(--text);
            font-size: 0.9rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.52rem;
        }

        .modal-body {
            background: #fff;
            padding: 1.44rem;
        }

        .modal-footer {
            background: rgba(248, 250, 252, 0.95);
            border-top: 1px solid var(--border);
            padding: 0.9rem 1.18rem;
            gap: 0.52rem;
        }

        .btn-modal-cancel,
        .btn-modal-save,
        .btn-modal-danger {
            border-radius: var(--r-sm);
            font-family: 'Inter', sans-serif;
            font-size: 0.79rem;
            font-weight: 600;
            padding: 0.53rem 1.2rem;
            transition: all .15s;
            cursor: pointer;
        }

        .btn-modal-cancel {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text-2);
        }

        .btn-modal-cancel:hover {
            border-color: rgba(53, 89, 224, 0.26);
            color: #1e3a8a;
        }

        .btn-modal-save {
            border: none;
            color: #fff;
            background: linear-gradient(135deg, #3559e0, #0ea5e9);
            box-shadow: 0 9px 18px rgba(53, 89, 224, 0.24);
        }

        .btn-modal-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(53, 89, 224, 0.28);
        }

        .btn-modal-danger {
            border: 1px solid rgba(185, 28, 28, 0.22);
            color: #b91c1c;
            background: rgba(185, 28, 28, 0.08);
        }

        .btn-modal-danger:hover {
            background: rgba(185, 28, 28, 0.14);
        }

        .delete-confirm-box {
            border: 1px solid rgba(185, 28, 28, 0.18);
            background: rgba(185, 28, 28, 0.06);
            border-radius: var(--r-sm);
            padding: 1.12rem;
            text-align: center;
            color: #991b1b;
            font-size: 0.84rem;
        }

        .delete-confirm-box .di {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.75;
        }

        .table-footer {
            padding: 0.84rem 1.12rem;
            border-top: 1px solid var(--border);
            background: rgba(248, 250, 252, 0.9);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.55rem;
        }

        .table-footer-meta {
            color: var(--text-3);
            font-size: 0.72rem;
        }

        .filter-select {
            border-radius: var(--r-sm);
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            padding: 0.51rem 2rem 0.51rem 0.75rem;
            cursor: pointer;
            -webkit-appearance: none;
            outline: none;
            transition: border-color .16s, box-shadow .16s;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
        }

        .filter-select:focus {
            border-color: rgba(53, 89, 224, 0.35);
            box-shadow: 0 0 0 3px rgba(53, 89, 224, 0.11);
        }

        .amount-positive { color: #0f766e; font-weight: 600; }
        .amount-neutral { color: var(--text-3); }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(51, 65, 85, 0.25); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(51, 65, 85, 0.4); }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-wrapper { margin-left: 0; }
            .stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .page-content { padding: 1rem; }
            .topbar { padding: 0 1rem; }
        }
    </style>
</head>
<body>
