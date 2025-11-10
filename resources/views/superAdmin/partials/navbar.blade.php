<!-- Super Admin Navigation Bar -->
<nav class="app-navbar">
    <div class="container">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('superadmin/dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->is('superadmin/master*') ? 'active' : '' }}" 
                   data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-database me-1"></i> Lihat Data Master <i class="fas fa-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/users') ? 'active' : '' }}" href="{{ route('superadmin.master.users') }}">
                            <i class="fas fa-users me-1"></i> Data User/Petugas
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/sampah-terkelola') ? 'active' : '' }}" href="{{ route('superadmin.master.sampah-terkelola') }}">
                            <i class="fas fa-recycle me-1"></i> Data Sampah Terkelola
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/sampah-diserahkan') ? 'active' : '' }}" href="{{ route('superadmin.master.sampah-diserahkan') }}">
                            <i class="fas fa-truck me-1"></i> Data Sampah Diserahkan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/lokasi-asal') ? 'active' : '' }}" href="{{ route('superadmin.master.lokasi-asal') }}">
                            <i class="fas fa-map-marker-alt me-1"></i> Lokasi Asal Sampah
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/jenis-sampah') ? 'active' : '' }}" href="{{ route('superadmin.master.jenis-sampah') }}">
                            <i class="fas fa-trash-alt me-1"></i> Jenis Sampah
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/tujuan-sampah') ? 'active' : '' }}" href="{{ route('superadmin.master.tujuan-sampah') }}">
                            <i class="fas fa-arrow-right me-1"></i> Tujuan Sampah
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->is('superadmin/laporan*') ? 'active' : '' }}" 
                   data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                    <i class="fas fa-chart-bar me-1"></i> Kelola Laporan <i class="fas fa-chevron-down ms-1"></i>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/harian') ? 'active' : '' }}" href="{{ route('superadmin.laporan.harian') }}">
                            <i class="fas fa-calendar-day me-1"></i> Laporan Harian
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/mingguan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.mingguan') }}">
                            <i class="fas fa-calendar-week me-1"></i> Laporan Mingguan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/bulanan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.bulanan') }}">
                            <i class="fas fa-calendar-alt me-1"></i> Laporan Bulanan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/tahunan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.tahunan') }}">
                            <i class="far fa-calendar-alt me-1"></i> Laporan Tahunan
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>