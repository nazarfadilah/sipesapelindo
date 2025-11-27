<div class="app-header">
    <div class="container-fluid h-100">
        <div class="d-flex justify-content-between align-items-center px-4 h-100">
            <div>
                @php
                    $title = 'Dashboard Admin';
                    if(request()->routeIs('admin.dashboard')) {
                        $title = 'Dashboard Admin';
                    } elseif(request()->routeIs('admin.kelola-petugas*')) {
                        $title = 'Kelola Data Petugas';
                    } elseif(request()->routeIs('admin.dokumen.*')) {
                        $title = 'Kelola Dokumen';
                    } elseif(request()->routeIs('admin.data.sampah-terkelola')) {
                        $title = 'Sampah Terkelola';
                    } elseif(request()->routeIs('admin.data.sampah-diserahkan')) {
                        $title = 'Sampah Diserahkan';
                    } elseif(request()->routeIs('admin.master.lokasi-asal')) {
                        $title = 'Lokasi Asal';
                    } elseif(request()->routeIs('admin.master.jenis-sampah')) {
                        $title = 'Jenis Sampah';
                    } elseif(request()->routeIs('admin.master.tujuan-sampah')) {
                        $title = 'Tujuan Sampah';
                    } elseif(request()->routeIs('admin.master.*')) {
                        $title = 'Kelola Data Master';
                    }
                @endphp

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/pwaste.png') }}" alt="Logo PWaste" style="width: 60px; height: auto; cursor: pointer; margin-left: -10px; background-color: white; padding: 8px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    </a>
                    <div>
                        <h4 class="app-title">{{ $title }}</h4>
                        <p class="app-subtitle">Aplikasi Pengelolaan Sampah Pelabuhan Banjarmasin</p>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="time-display" id="timeDisplay">--:--:-- WITA</div>

                <script>
                    (function(){
                        function pad(n){ return n.toString().padStart(2,'0'); }
                        function updateWITA(){
                            const now = new Date();
                            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
                            const wita = new Date(utc + 8 * 3600000); // WITA = UTC+8
                            const h = pad(wita.getHours());
                            const m = pad(wita.getMinutes());
                            const s = pad(wita.getSeconds());
                            const el = document.getElementById('timeDisplay');
                            if(el) el.textContent = `${h}:${m}:${s} WITA`;
                        }
                        updateWITA();
                        setInterval(updateWITA, 1000);
                    })();
                </script>
                <div class="d-flex align-items-center">
                    <span class="me-2" style="font-size: 0.9rem;">Selamat Datang, Admin</span>
                    <i class="fas fa-circle text-success" style="font-size: 0.5rem;"></i>
                </div>
                <div class="text-white" style="font-size: 0.9rem;">
                    {{ date('l, d F Y') }}
                </div>
                <a href="{{ route('logout') }}" class="logout-btn ms-3" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<div class="app-navbar">
    <div class="container-fluid px-4">
        <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.kelola-petugas') }}" class="nav-link {{ request()->routeIs('admin.kelola-petugas*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i>Kelola Data Petugas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.dokumen.index') }}" class="nav-link {{ request()->routeIs('admin.dokumen.*') ? 'active' : '' }}">
                    <i class="fas fa-folder me-2"></i>Kelola Dokumen
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.data.*') ? 'active' : '' }}" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-chart-bar me-2"></i>Lihat Data Sampah
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.data.sampah-terkelola') }}">Sampah Terkelola</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.data.sampah-diserahkan') }}">Sampah Diserahkan</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.master.*') ? 'active' : '' }}" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-database me-2"></i>Kelola Data Master
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.master.lokasi-asal') }}">Lokasi Asal</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.master.jenis-sampah') }}">Jenis Sampah</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.master.tujuan-sampah') }}">Tujuan Sampah</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="fas fa-folder me-2"></i>Laporan
                </a>
            </li>
        </ul>
    </div>
</div>