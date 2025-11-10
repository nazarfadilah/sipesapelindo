<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin Dashboard') - SIPESA Pelindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #1E3F8C;
            --secondary-color: #00BFFF;
            --background-color: #f4f6f9;
            --text-color: #333;
            --white: #ffffff;
        }
        
        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .app-container {
            flex: 1;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        .app-header {
            background-color: var(--white);
            color: var(--primary-color);
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
        
        .app-navbar {
            background-color: var(--primary-color);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .app-navbar .nav-link {
            color: var(--white);
            padding: 8px 15px;
            border-radius: 5px;
            margin-right: 5px;
            font-weight: 500;
        }
        
        .app-navbar .nav-link:hover,
        .app-navbar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
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
    @stack('scripts')
</body>
</html>