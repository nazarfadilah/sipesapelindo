<!-- Super Admin Header -->
<div class="app-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="header-title">Dashboard</h1>
            <p class="header-subtitle">Aplikasi Pengelolaan Sampah dan LB3 Pelindo Subregional Samarinda</p>
        </div>
        <div class="d-flex flex-column align-items-end">
            <div class="current-date mb-2">{{ date('d-m-Y H:i') }}</div>
            <div class="user-info">
                <span>Selamat Datang, Super Admin </span>
                <a href="{{ route('logout') }}" class="btn btn-sm btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>