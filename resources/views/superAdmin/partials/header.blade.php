<!-- Super Admin Header -->
<header class="app-header bg-primary w-100">
    <div class="w-100 py-2">
        <div class="row mx-0">
            <div class="col-6 ps-4">
                <h4 class="page-title mb-0 text-white">Dashboard Super Admin</h4>
                <small class="text-white-50">Aplikasi Pengelolaan Sampah Pelabuhan Banjarmasin</small>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end align-items-center h-100">
<<<<<<< HEAD
                    <div class="time-display me-3" id="timeDisplay" style="font-size: 14px; color: #fff;">
=======
                    <div class="time-display me-3" id="timeDisplay" style="background-color: #ffffff; color: #1E3F8C; font-size: 14px; font-weight: 600; padding: 6px 12px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
>>>>>>> 6e89e57f051786d5604936eff210660844d38f90
                        --:--:-- WITA
                    </div>

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
                    <div class="d-flex align-items-center text-white me-3">
                        <span>Selamat Datang, Super Admin</span>
                        <i class="fas fa-circle text-success ms-2" style="font-size: 8px;"></i>
                    </div>
                    <div class="text-white me-3" style="font-size: 14px;">
                        {{ now()->isoFormat('dddd, DD MMMM YYYY') }}
                    </div>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="logout-btn">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>