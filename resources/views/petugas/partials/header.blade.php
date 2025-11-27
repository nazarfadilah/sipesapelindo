<!-- Header Section -->
<header class="app-header bg-primary">
    <div class="container-fluid px-4 py-2">
        <div class="row">
            <div class="col-6">
                <h4 class="page-title mb-0 text-white">@yield('title', 'Dashboard')</h4>
                <small class="text-white-50">Aplikasi Pengelolaan Sampah Pelabuhan Banjarmasin</small>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end align-items-center h-100">
                    <div class="time-display me-3" id="timeDisplay" style="font-size: 12px; color: #333;">
                        00.00.00 AM WITA
                    </div>
                    <div class="d-flex align-items-center text-white me-2">
                        <span>Selamat Datang, Petugas</span>
                        <i class="fas fa-circle text-success ms-2" style="font-size: 5px;"></i>
                    </div>
                    <div class="text-white me-3" style="font-size: 14px;">
                        {{ now()->isoFormat('dddd, DD MMMM YYYY') }}
                    </div>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="logout-btn">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none" >
                        @csrf
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var logoutLink = document.querySelector('.logout-btn');
                            if (!logoutLink) return;
                            // Remove existing inline onclick to ensure our confirmation runs first
                            logoutLink.removeAttribute('onclick');
                            logoutLink.addEventListener('click', function (e) {
                                e.preventDefault();
                                if (confirm('Apakah Anda yakin ingin keluar?')) {
                                    document.getElementById('logout-form').submit();
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</header>