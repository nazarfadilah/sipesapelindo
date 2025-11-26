<!-- Super Admin Navigation Bar -->
<nav class="app-navbar bg-white w-100">
    <div class="w-100">
        <div class="d-flex gap-3 py-2 justify-content-center">
            <a href="{{ route('superadmin.dashboard') }}" class="btn d-inline-flex align-items-center {{ request()->is('superadmin/dashboard') ? 'btn-primary text-white' : 'btn-outline-primary' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="ms-2">Dashboard</span>
            </a>
            <div class="dropdown">
                <button class="btn dropdown-toggle d-inline-flex align-items-center {{ request()->is('superadmin/master*') ? 'btn-primary text-white' : 'btn-outline-primary' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-database"></i>
                    <span class="ms-2">Lihat Data Master</span>
                </button>
                <ul class="dropdown-menu mt-1">
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/users') ? 'active' : '' }}" href="{{ route('superadmin.master.users') }}">
                            <i class="fas fa-users me-2"></i>Data User/Petugas
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/sampah-terkelola') ? 'active' : '' }}" href="{{ route('superadmin.master.sampah-terkelola') }}">
                            <i class="fas fa-recycle me-2"></i>Data Sampah Terkelola
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/sampah-diserahkan') ? 'active' : '' }}" href="{{ route('superadmin.master.sampah-diserahkan') }}">
                            <i class="fas fa-truck me-2"></i>Data Sampah Diserahkan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/lokasi-asal') ? 'active' : '' }}" href="{{ route('superadmin.master.lokasi-asal') }}">
                            <i class="fas fa-map-marker-alt me-2"></i>Lokasi Asal Sampah
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/jenis-sampah') ? 'active' : '' }}" href="{{ route('superadmin.master.jenis-sampah') }}">
                            <i class="fas fa-trash-alt me-2"></i>Jenis Sampah
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/master/tujuan-sampah') ? 'active' : '' }}" href="{{ route('superadmin.master.tujuan-sampah') }}">
                            <i class="fas fa-arrow-right me-2"></i>Tujuan Sampah
                        </a>
                    </li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle d-inline-flex align-items-center {{ request()->is('superadmin/laporan*') ? 'btn-primary text-white' : 'btn-outline-primary' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-chart-bar"></i>
                    <span class="ms-2">Kelola Laporan</span>
                </button>
                <ul class="dropdown-menu mt-1">
                    <li>
<<<<<<< HEAD
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/harian') ? 'active text-white' : '' }}" href="{{ route('superadmin.laporan.harian') }}">
                            Laporan Harian
=======
                        <a class="dropdown-item {{ request()->is('superadmin/laporan') && !request()->is('superadmin/laporan/*') ? 'active text-white' : '' }}" href="{{ route('superadmin.laporan.index') }}">
                            <i class="fas fa-file-export me-2"></i>Export Laporan
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/harian') ? 'active text-white' : '' }}" href="{{ route('superadmin.laporan.harian') }}">
                            <i class="fas fa-calendar-day me-2"></i>Laporan Harian
>>>>>>> 6e89e57f051786d5604936eff210660844d38f90
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/mingguan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.mingguan') }}">
<<<<<<< HEAD
                            Laporan Mingguan
=======
                            <i class="fas fa-calendar-week me-2"></i>Laporan Mingguan
>>>>>>> 6e89e57f051786d5604936eff210660844d38f90
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/bulanan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.bulanan') }}">
<<<<<<< HEAD
                            Laporan Bulanan
=======
                            <i class="fas fa-calendar-alt me-2"></i>Laporan Bulanan
>>>>>>> 6e89e57f051786d5604936eff210660844d38f90
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('superadmin/laporan/tahunan') ? 'active' : '' }}" href="{{ route('superadmin.laporan.tahunan') }}">
<<<<<<< HEAD
                            Laporan Tahunan
=======
                            <i class="fas fa-calendar me-2"></i>Laporan Tahunan
>>>>>>> 6e89e57f051786d5604936eff210660844d38f90
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>