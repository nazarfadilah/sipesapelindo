<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Super Admin Dashboard') - PWaste Pelindo Subregional Kalimantan</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-blue: #1E3F8C;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            min-width: 100vw;
            overflow-x: hidden;
        }

        /* Navbar custom styles */
        .app-navbar .btn {
            padding: 0.3rem 1rem;
            border-width: 0.75px;
            font-weight: 300;
            border-color: rgba(0, 0, 0, 0.1);
        }

        .app-navbar .btn-outline-primary {
            color: var(--primary-blue);
            border-color: rgba(0, 0, 0, 0.1);
            background-color: transparent;
            box-shadow: none;
        }

        .app-navbar .btn-outline-primary:hover {
            color: var(--primary-blue);
            background-color: transparent;
            border-color: rgba(0, 0, 0, 0.1);
            box-shadow: none;
        }

        .app-navbar .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .app-navbar .dropdown-item.active {
            background-color: var(--primary-blue);
        }

        main {
            flex: 1;
            width: 100%;
        }

        .app-container {
            flex: 1;
            /* padding: 20px; */
            width: 100%;
            max-width: auto;
            margin: 0 auto;
        }

        /* Header Styling */
        .app-header {
            background-color: var(--primary-blue) !important;
            color: var(--white) !important;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 14px;
            margin-bottom: 0;
            color: #666;
        }

        /* Navbar Styling */
        .app-navbar {
            background-color: var(--white);
            padding: 0.55rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .app-navbar .nav-link {
            color: #333;
            padding: 0.35rem 0.5rem;
            font-size: 0.85rem;
            line-height: 1;
            min-width: 0;
            height: auto;
            margin: 0 0.25rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .app-navbar .nav-link:hover,
        .app-navbar .nav-link.active {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        .dropdown-menu {
            background-color: var(--white);
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px;
            min-width: 220px;
        }

        .dropdown-item {
            padding: 8px 15px;
            color: var(--text-color);
            border-radius: 4px;
            margin-bottom: 2px;
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: rgba(30, 63, 140, 0.1);
            color: var(--primary-color);
        }

        .content-area {
            background-color: var(--white);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .filter-form {
            background-color: var(--primary-color);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            color: var(--white);
        }

        .chart-container {
            background-color: var(--primary-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            height: 350px;
            color: var(--white);
        }

        .stats-card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-card .title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stats-card .value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }

        .stats-table th {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 10px;
            text-align: center;
        }

        .stats-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .stats-table tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .app-footer {
            background-color: var(--primary-color);
            color: var(--white);
            text-align: center;
            padding: 15px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            color: var(--text-color);
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #0099cc;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
        }

        .current-date {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .user-info {
            color: var(--primary-color);
            font-size: 14px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: #F0AD4E;
            color: #333;
            font-weight: 600;
            text-align: center;
            padding: 10px;
        }

        .data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .data-table tr:last-child {
            background-color: #3498db;
            color: white;
            font-weight: 600;
        }

        .data-table tr:nth-child(odd):not(:last-child) {
            background-color: #f9f9f9;
        }

        .pie-chart-legend {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            color: white;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            margin-right: 8px;
            border-radius: 50%;
        }
        /* Footer Styling */
        .app-footer {
            background-color: var(--white);
            padding: 1rem;
            text-align: center;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
            margin-top: 2rem;
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary:hover {
            background-color: #15306B;
            border-color: #15306B;
        }

        .btn-logout {
            background-color: #dc3545;
            color: var(--white);
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #bb2d3b;
            color: var(--white);
        }

        /* Utility Classes */
        .current-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .user-info {
            font-size: 0.9rem;
            color: var(--white);
        }

        /* Data Table Styling */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            background-color: var(--white);
        }

        .data-table th {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 1rem;
            text-align: left;
        }

        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* Chart Styling */
        .pie-chart-legend {
            padding: 1rem;
            background-color: var(--white);
            border-radius: 4px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 0.5rem;
            border-radius: 3px;
        }

        /* Content Area Styling */
        .content-area {
            background-color: var(--white);
            padding: 1.5rem;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }

        .filter-form {
            margin-bottom: 2rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-container">
        @include('superAdmin.partials.header')
        @include('superAdmin.partials.navbar')

        @yield('content')

        @include('superAdmin.partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>
</html>
