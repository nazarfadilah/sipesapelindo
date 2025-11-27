<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\SampahDiserahkan;
use App\Models\SampahTerkelola;
use App\Models\TujuanSampah;
use App\Models\User;
use App\Models\Dokumen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan statistik data sampah
     */
    public function dashboard(Request $request)
    {
        // Set default filter jika tidak ada
        $filterType = $request->filter_type ?? 'year';
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $week = $request->week ?? 1;
        $day = $request->day ?? date('Y-m-d');

        // Query untuk data berdasarkan filter
        $sampahTerkelolaQuery = SampahTerkelola::query()
            ->join('lokasi_asals', 'sampah_terkelolas.id_lokasi', '=', 'lokasi_asals.id')
            ->join('jenis', 'sampah_terkelolas.id_jenis', '=', 'jenis.id')
            ->select(
                'lokasi_asals.nama_lokasi as lokasi',
                'jenis.nama_jenis as jenis',
                DB::raw('SUM(sampah_terkelolas.jumlah_berat) as total_berat')
            );

        $sampahDiserahkanQuery = SampahDiserahkan::query()
            ->join('lokasi_asals', 'sampah_diserahkans.id_lokasi', '=', 'lokasi_asals.id')
            ->join('jenis', 'sampah_diserahkans.id_jenis', '=', 'jenis.id')
            ->join('tujuan_sampahs', 'sampah_diserahkans.id_diserahkan', '=', 'tujuan_sampahs.id')
            ->select(
                'lokasi_asals.nama_lokasi as lokasi',
                'jenis.nama_jenis as jenis',
                'tujuan_sampahs.nama_tujuan as tujuan',
                DB::raw('SUM(sampah_diserahkans.jumlah_berat) as total_berat')
            );

        // Filter berdasarkan tipe filter yang dipilih
        switch ($filterType) {
            case 'year':
                $sampahTerkelolaQuery->whereYear('tgl', $year);
                $sampahDiserahkanQuery->whereYear('tgl', $year);
                $periodText = "Tahun $year";
                break;

            case 'month':
                $sampahTerkelolaQuery->whereYear('tgl', $year)->whereMonth('tgl', $month);
                $sampahDiserahkanQuery->whereYear('tgl', $year)->whereMonth('tgl', $month);
                $periodText = "Bulan " . Carbon::createFromDate($year, $month, 1)->format('F Y');
                break;

            case 'week':
                // Hitung tanggal awal dan akhir dari minggu yang dipilih
                $firstDayOfMonth = Carbon::createFromDate($year, $month, 1);
                $weekStart = $firstDayOfMonth->copy()->addDays(($week - 1) * 7);       
                $weekEnd = $weekStart->copy()->addDays(6);

                $sampahTerkelolaQuery->whereBetween('tgl', [$weekStart, $weekEnd]);    
                $sampahDiserahkanQuery->whereBetween('tgl', [$weekStart, $weekEnd]);   
                $periodText = "Minggu $week, " . $weekStart->format('d') . " - " . $weekEnd->format('d F Y');
                break;

            case 'day':
                $sampahTerkelolaQuery->whereDate('tgl', $day);
                $sampahDiserahkanQuery->whereDate('tgl', $day);
                $periodText = "Tanggal " . Carbon::parse($day)->format('d F Y');       
                break;
        }

        // Grup hasil berdasarkan lokasi dan jenis
        $sampahTerkelolaQuery->groupBy('lokasi_asals.nama_lokasi', 'jenis.nama_jenis');
        $sampahDiserahkanQuery->groupBy('lokasi_asals.nama_lokasi', 'jenis.nama_jenis', 'tujuan_sampahs.nama_tujuan');

        // Ambil hasil query
        $sampahTerkelola = $sampahTerkelolaQuery->get();
        $sampahDiserahkan = $sampahDiserahkanQuery->get();

        // Olah data untuk chart jenis sampah
        $jenisSampah = Jenis::all();
        $jenisSampahDataTerkelola = [];
        $jenisSampahDataDiserahkan = [];
        $jenisSampahLabels = [];
        $jenisSampahColors = [
            'rgb(255, 0, 0)',    // Red
            'rgb(0, 128, 0)',     // Green
            'rgb(255, 255, 0)',   // Yellow
            'rgb(0, 0, 255)',     // Blue
            'rgb(128, 0, 128)',   // Purple
            'rgb(255, 165, 0)'    // Orange
        ];

        foreach ($jenisSampah as $index => $jenis) {
            $jenisSampahLabels[] = $jenis->nama_jenis;

            // Hitung total sampah terkelola per jenis
            $totalTerkelola = $sampahTerkelola
                ->where('jenis', $jenis->nama_jenis)
                ->sum('total_berat');

            $jenisSampahDataTerkelola[] = $totalTerkelola;

            // Hitung total sampah diserahkan per jenis
            $totalDiserahkan = $sampahDiserahkan
                ->where('jenis', $jenis->nama_jenis)
                ->sum('total_berat');

            $jenisSampahDataDiserahkan[] = $totalDiserahkan;
        }

        // Olah data untuk chart total sampah per lokasi
        $lokasiSampah = LokasiAsal::all();
        $lokasiSampahLabels = [];
        $lokasiSampahData = [];
        $lokasiSampahDataDiserahkan = [];

        foreach ($lokasiSampah as $lokasi) {
            $lokasiSampahLabels[] = $lokasi->nama_lokasi;

            // Hitung total sampah terkelola per lokasi
            $totalTerkelola = $sampahTerkelola
                ->where('lokasi', $lokasi->nama_lokasi)
                ->sum('total_berat');

            $lokasiSampahData[] = $totalTerkelola;

            // Hitung total sampah diserahkan per lokasi
            $totalDiserahkan = $sampahDiserahkan
                ->where('lokasi', $lokasi->nama_lokasi)
                ->sum('total_berat');

            $lokasiSampahDataDiserahkan[] = $totalDiserahkan;
        }

        // Data untuk tabel rekap
        $rekapData = [];
        foreach ($lokasiSampah as $lokasi) {
            $lokasiNama = $lokasi->nama_lokasi;

            // Total sampah terkelola untuk lokasi ini
            $totalSampahBiasa = $sampahTerkelola
                ->where('lokasi', $lokasiNama)
                ->where('jenis', '!=', 'LB3')
                ->sum('total_berat');

            $totalSampahLB3 = $sampahTerkelola
                ->where('lokasi', $lokasiNama)
                ->where('jenis', 'LB3')
                ->sum('total_berat');

            $totalSampahAll = $totalSampahBiasa + $totalSampahLB3;

            // Total sampah terkelola
            $totalTerkelola = $sampahTerkelola
                ->where('lokasi', $lokasiNama)
                ->sum('total_berat');

            // Persentase terkelola
            $persenTerkelola = $totalSampahAll > 0
                ? round(($totalTerkelola / $totalSampahAll) * 100, 2)
                : 0;

            // Total sampah diserahkan untuk lokasi ini
            $totalDiserahkanBiasa = $sampahDiserahkan
                ->where('lokasi', $lokasiNama)
                ->where('jenis', '!=', 'LB3')
                ->sum('total_berat');

            $totalDiserahkanLB3 = $sampahDiserahkan
                ->where('lokasi', $lokasiNama)
                ->where('jenis', 'LB3')
                ->sum('total_berat');

            // Persentase diserahkan
            $totalDiserahkan = $totalDiserahkanBiasa + $totalDiserahkanLB3;
            $totalKeseluruhan = $totalTerkelola + $totalDiserahkan;
            
            $persenDiserahkan = $totalSampahAll > 0
                ? round((($totalDiserahkanBiasa + $totalDiserahkanLB3) / $totalSampahAll) * 100, 2)
                : 0;
                
            // Persentase dari total keseluruhan (terkelola + diserahkan)
            $persenTerkelolaFromTotal = $totalKeseluruhan > 0
                ? round(($totalTerkelola / $totalKeseluruhan) * 100, 2)
                : 0;
                
            $persenDiserahkanFromTotal = $totalKeseluruhan > 0
                ? round($totalDiserahkan / $totalKeseluruhan * 100, 2)
                : 0;

            $rekapData[] = [
                'lokasi' => $lokasiNama,
                'sampah_biasa' => round($totalSampahBiasa, 2),
                'sampah_lb3' => round($totalSampahLB3, 2),
                'total_sampah' => round($totalSampahAll, 2),
                'terkelola' => round($totalTerkelola, 2),
                'persen_terkelola' => $persenTerkelola,
                'diserahkan_biasa' => round($totalDiserahkanBiasa, 2),
                'diserahkan_lb3' => round($totalDiserahkanLB3, 2),
                'persen_diserahkan' => $persenDiserahkan,
                'total_keseluruhan' => round($totalKeseluruhan, 2),
                'persen_terkelola_from_total' => $persenTerkelolaFromTotal,
                'persen_diserahkan_from_total' => $persenDiserahkanFromTotal
            ];
        }

        // Hitung total keseluruhan
        $totalSampahBiasa = array_sum(array_column($rekapData, 'sampah_biasa'));       
        $totalSampahLB3 = array_sum(array_column($rekapData, 'sampah_lb3'));
        $totalSampahAll = array_sum(array_column($rekapData, 'total_sampah'));
        $totalTerkelola = array_sum(array_column($rekapData, 'terkelola'));
        $totalDiserahkanBiasa = array_sum(array_column($rekapData, 'diserahkan_biasa'));
        $totalDiserahkanLB3 = array_sum(array_column($rekapData, 'diserahkan_lb3'));
        $totalDiserahkan = $totalDiserahkanBiasa + $totalDiserahkanLB3;
        $totalKeseluruhan = $totalTerkelola + $totalDiserahkan;

        $persenTerkelolaTotal = $totalSampahAll > 0
            ? round(($totalTerkelola / $totalSampahAll) * 100, 2)
            : 0;

        $persenDiserahkanTotal = $totalSampahAll > 0
            ? round((($totalDiserahkanBiasa + $totalDiserahkanLB3) / $totalSampahAll) * 100, 2)
            : 0;
            
        $persenTerkelolaFromTotal = $totalKeseluruhan > 0
            ? round(($totalTerkelola / $totalKeseluruhan) * 100, 2)
            : 0;
            
        $persenDiserahkanFromTotal = $totalKeseluruhan > 0
            ? round($totalDiserahkan / $totalKeseluruhan * 100, 2)
            : 0;

        return view('admin.dashboard', [
            'periodText' => $periodText,
            'jenisSampahLabels' => json_encode($jenisSampahLabels),
            'jenisSampahDataTerkelola' => json_encode($jenisSampahDataTerkelola),
            'jenisSampahDataDiserahkan' => json_encode($jenisSampahDataDiserahkan),
            'jenisSampahColors' => json_encode($jenisSampahColors),
            'lokasiSampahLabels' => json_encode($lokasiSampahLabels),
            'lokasiSampahData' => json_encode($lokasiSampahData),
            'lokasiSampahDataDiserahkan' => json_encode($lokasiSampahDataDiserahkan),
            'rekapData' => $rekapData,
            'totalSampahBiasa' => round($totalSampahBiasa, 2),
            'totalSampahLB3' => round($totalSampahLB3, 2),
            'totalSampahAll' => round($totalSampahAll, 2),
            'totalTerkelola' => round($totalTerkelola, 2),
            'persenTerkelolaTotal' => $persenTerkelolaTotal,
            'totalDiserahkanBiasa' => round($totalDiserahkanBiasa, 2),
            'totalDiserahkanLB3' => round($totalDiserahkanLB3, 2),
            'persenDiserahkanTotal' => $persenDiserahkanTotal,
            'totalKeseluruhan' => round($totalKeseluruhan, 2),
            'persenTerkelolaFromTotal' => $persenTerkelolaFromTotal,
            'persenDiserahkanFromTotal' => $persenDiserahkanFromTotal
        ]);
    }

    /**
     * Menampilkan halaman kelola petugas
     */
    public function kelolaPetugas()
    {
        $petugas = User::where('role', '3')->get();
        return view('admin.kelola-petugas.index', compact('petugas'));
    }
    
    /**
     * Menampilkan form tambah petugas
     */
    public function tambahPetugas()
    {
        return view('admin.kelola-petugas.tambah');
    }
    
    /**
     * Menyimpan data petugas baru
     */
    public function storePetugas(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => '3',
        ]);
        
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Petugas berhasil ditambahkan');
    }
    
    /**
     * Menampilkan form edit petugas
     */
    public function editPetugas($id)
    {
        $petugas = User::findOrFail($id);
        return view('admin.kelola-petugas.edit', compact('petugas'));
    }
    
    /**
     * Memperbarui data petugas
     */
    public function updatePetugas(Request $request, $id)
    {
        $petugas = User::findOrFail($id);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        ];
        
        if($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }
        
        $request->validate($rules);
        
        $petugas->name = $request->name;
        $petugas->email = $request->email;
        
        if($request->filled('password')) {
            $petugas->password = bcrypt($request->password);
        }
        
        $petugas->save();
        
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Data petugas berhasil diperbarui');
    }
    
    /**
     * Menghapus data petugas
     */
    public function deletePetugas($id)
    {
        $petugas = User::findOrFail($id);
        $petugas->delete();
        
        return redirect()->route('admin.kelola-petugas')
            ->with('success', 'Petugas berhasil dihapus');
    }
    
    // Admin tidak memiliki akses untuk menambah/edit/hapus data sampah
    // Admin hanya dapat melihat data sampah yang sudah ada
    
    /**
     * Menampilkan data sampah terkelola
     */
    public function dataSampahTerkelola(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $sampahTerkelolas = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
            ->orderBy('tgl', 'desc')
            ->paginate($perPage);
            
        return view('admin.data.sampah-terkelola', compact('sampahTerkelolas'));
    }
    
    /**
     * Menampilkan data sampah diserahkan
     */
    public function dataSampahDiserahkan(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        
        $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl', 'desc')
            ->paginate($perPage);
            
        return view('admin.data.sampah-diserahkan', compact('sampahDiserahkans'));
    }
    
    /**
     * Menampilkan data dokumen (redirect ke dokumenIndex untuk backward compatibility)
     */
    public function dataDokumen(Request $request)
    {
        // Redirect to the new dokumen index page
        return redirect()->route('admin.dokumen.index');
    }
    
    /**
     * Menampilkan semua dokumen
     */
    public function dokumenIndex()
    {
        $dokumens = Dokumen::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.dokumen.dokumen', compact('dokumens'));
    }
    
    /**
     * Menampilkan form tambah dokumen
     */
    public function dokumenCreate()
    {
        return view('admin.dokumen.tambah-dokumen');
    }
    
    /**
     * Menyimpan dokumen baru
     */
    public function dokumenStore(Request $request)
    {
        $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'berlaku' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:berlaku',
        ]);
        
        $file = $request->file('file_dokumen');
        $filePath = $file->store('dokumen', 'public');
        
        Dokumen::create([
            'id_user' => Auth::id(),
            'no_dokumen' => 'DOK-' . date('YmdHis'),
            'judul_dokumen' => $request->judul_dokumen,
            'file_dokumen' => $filePath,
            'instansi_kerjasama' => 'Pelindo Subregional Banjarmasin',
            'berlaku' => $request->berlaku,
            'berakhir' => $request->berakhir,
            'keterangan_dokumen' => $request->keterangan_dokumen,
        ]);
        
        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil ditambahkan');
    }
    
    /**
     * Menampilkan form edit dokumen
     */
    public function dokumenEdit($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        return view('admin.dokumen.edit-dokumen', compact('dokumen'));
    }
    
    /**
     * Update dokumen
     */
    public function dokumenUpdate(Request $request, $id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        $request->validate([
            'judul_dokumen' => 'required|string|max:255',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'berlaku' => 'required|date',
            'berakhir' => 'required|date|after_or_equal:berlaku',
        ]);
        
        $data = [
            'judul_dokumen' => $request->judul_dokumen,
            'berlaku' => $request->berlaku,
            'berakhir' => $request->berakhir,
            'keterangan_dokumen' => $request->keterangan_dokumen,
        ];
        
        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama jika ada
            if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
                Storage::disk('public')->delete($dokumen->file_dokumen);
            }
            
            // Simpan file baru
            $file = $request->file('file_dokumen');
            $data['file_dokumen'] = $file->store('dokumen', 'public');
        }
        
        $dokumen->update($data);
        
        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil diperbarui');
    }
    
    /**
     * Hapus dokumen
     */
    public function dokumenDestroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        
        // Hapus file jika ada
        if ($dokumen->file_dokumen && Storage::disk('public')->exists($dokumen->file_dokumen)) {
            Storage::disk('public')->delete($dokumen->file_dokumen);
        }
        
        $dokumen->delete();
        
        return redirect()->route('admin.dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }
    
    /**
     * Menampilkan halaman master data lokasi asal
     */
    public function masterLokasiAsal()
    {
        $lokasiAsal = LokasiAsal::all();
        return view('admin.master.lokasi-asal', compact('lokasiAsal'));
    }
    
    /**
     * Menyimpan data lokasi asal baru
     */
    public function storeLokasiAsal(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:lokasi_asals,nama_lokasi',
        ]);
        
        LokasiAsal::create([
            'nama_lokasi' => $request->nama
        ]);
        
        return redirect()->route('admin.master.lokasi-asal')
            ->with('success', 'Lokasi asal berhasil ditambahkan');
    }
    
    /**
     * Memperbarui data lokasi asal
     */
    public function updateLokasiAsal(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:lokasi_asals,nama_lokasi,'.$id,
        ]);
        
        $lokasiAsal = LokasiAsal::findOrFail($id);
        $lokasiAsal->nama_lokasi = $request->nama;
        $lokasiAsal->save();
        
        return redirect()->route('admin.master.lokasi-asal')
            ->with('success', 'Lokasi asal berhasil diperbarui');
    }
    
    /**
     * Menghapus data lokasi asal
     */
    public function deleteLokasiAsal($id)
    {
        $lokasiAsal = LokasiAsal::findOrFail($id);
        $lokasiAsal->delete();
        
        return redirect()->route('admin.master.lokasi-asal')
            ->with('success', 'Lokasi asal berhasil dihapus');
    }
    
    /**
     * Menampilkan halaman master data jenis sampah
     */
    public function masterJenisSampah()
    {
        $jenisSampah = Jenis::all();
        return view('admin.master.jenis-sampah', compact('jenisSampah'));
    }
    
    /**
     * Menyimpan data jenis sampah baru
     */
    public function storeJenisSampah(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis,nama_jenis',
        ]);
        
        Jenis::create([
            'nama_jenis' => $request->nama
        ]);
        
        return redirect()->route('admin.master.jenis-sampah')
            ->with('success', 'Jenis sampah berhasil ditambahkan');
    }
    
    /**
     * Memperbarui data jenis sampah
     */
    public function updateJenisSampah(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis,nama_jenis,'.$id,
        ]);
        
        $jenisSampah = Jenis::findOrFail($id);
        $jenisSampah->nama_jenis = $request->nama;
        $jenisSampah->save();
        
        return redirect()->route('admin.master.jenis-sampah')
            ->with('success', 'Jenis sampah berhasil diperbarui');
    }
    
    /**
     * Menghapus data jenis sampah
     */
    public function deleteJenisSampah($id)
    {
        $jenisSampah = Jenis::findOrFail($id);
        $jenisSampah->delete();
        
        return redirect()->route('admin.master.jenis-sampah')
            ->with('success', 'Jenis sampah berhasil dihapus');
    }
    
    /**
     * Menampilkan halaman master data tujuan sampah
     */
    public function masterTujuanSampah()
    {
        $tujuanSampah = TujuanSampah::all();
        return view('admin.master.tujuan-sampah', compact('tujuanSampah'));
    }
    
    /**
     * Menyimpan data tujuan sampah baru
     */
    public function storeTujuanSampah(Request $request)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan',
            'kategori' => 'required|string|in:sampah,lb3',
            'alamat' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        TujuanSampah::create([
            'nama_tujuan' => $request->nama_tujuan,
            'kategori' => $request->kategori,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);
        
        return redirect()->route('admin.master.tujuan-sampah')
            ->with('success', 'Tujuan sampah berhasil ditambahkan');
    }
    
    /**
     * Memperbarui data tujuan sampah
     */
    public function updateTujuanSampah(Request $request, $id)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan,'.$id,
            'kategori' => 'required|string|in:sampah,lb3',
            'alamat' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $tujuanSampah = TujuanSampah::findOrFail($id);
        $tujuanSampah->nama_tujuan = $request->nama_tujuan;
        $tujuanSampah->kategori = $request->kategori;
        $tujuanSampah->alamat = $request->alamat;
        $tujuanSampah->status = $request->status;
        $tujuanSampah->save();
        
        return redirect()->route('admin.master.tujuan-sampah')
            ->with('success', 'Tujuan sampah berhasil diperbarui');
    }
    
    /**
     * Menghapus data tujuan sampah
     */
    public function deleteTujuanSampah($id)
    {
        $tujuanSampah = TujuanSampah::findOrFail($id);
        $tujuanSampah->delete();
        
        return redirect()->route('admin.master.tujuan-sampah')
            ->with('success', 'Tujuan sampah berhasil dihapus');
    }

    /**
     * Menampilkan form edit sampah terkelola
     */
    public function editSampahTerkelola($id_sampah)
    {
        $sampah = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])->where('id_sampah', $id_sampah)->firstOrFail();
        $lokasiAsals = LokasiAsal::all();
        $jenisList = Jenis::all();
        
        return view('admin.data.edit-sampah-terkelola', compact('sampah', 'lokasiAsals', 'jenisList'));
    }

    /**
     * Memperbarui data sampah terkelola
     */
    public function updateSampahTerkelola(Request $request, $id_sampah)
    {
        $sampah = SampahTerkelola::where('id_sampah', $id_sampah)->firstOrFail();
        
        $request->validate([
            'id_lokasi' => 'required|exists:lokasi_asals,id',
            'id_jenis' => 'required|exists:jenis,id',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $sampah->id_lokasi = $request->id_lokasi;
        $sampah->id_jenis = $request->id_jenis;
        $sampah->jumlah_berat = $request->jumlah_berat;
        $sampah->tgl = $request->tgl;
        
        if($request->hasFile('foto')) {
            if($sampah->foto && Storage::exists('public/' . $sampah->foto)) {
                Storage::delete('public/' . $sampah->foto);
            }
            
            $file = $request->file('foto');
            $filePath = $file->store('sampah-terkelola', 'public');
            $sampah->foto = $filePath;
        }
        
        $sampah->save();
        
        return redirect()->route('admin.data.sampah-terkelola')
            ->with('success', 'Data sampah terkelola berhasil diperbarui');
    }

    /**
     * Menampilkan form edit sampah diserahkan
     */
    public function editSampahDiserahkan($id)
    {
        $sampah = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])->findOrFail($id);
        $lokasiAsals = LokasiAsal::all();
        $jenisList = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        
        return view('admin.data.edit-sampah-diserahkan', compact('sampah', 'lokasiAsals', 'jenisList', 'tujuanSampahs'));
    }

    /**
     * Memperbarui data sampah diserahkan
     */
    public function updateSampahDiserahkan(Request $request, $id)
    {
        $sampah = SampahDiserahkan::findOrFail($id);
        
        $request->validate([
            'id_lokasi' => 'required|exists:lokasi_asals,id',
            'id_jenis' => 'required|exists:jenis,id',
            'id_diserahkan' => 'required|exists:tujuan_sampahs,id',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $sampah->id_lokasi = $request->id_lokasi;
        $sampah->id_jenis = $request->id_jenis;
        $sampah->id_diserahkan = $request->id_diserahkan;
        $sampah->jumlah_berat = $request->jumlah_berat;
        $sampah->tgl = $request->tgl;
        
        if($request->hasFile('foto')) {
            if($sampah->foto && Storage::exists('public/' . $sampah->foto)) {
                Storage::delete('public/' . $sampah->foto);
            }
            
            $file = $request->file('foto');
            $filePath = $file->store('sampah-diserahkan', 'public');
            $sampah->foto = $filePath;
        }
        
        $sampah->save();
        
        return redirect()->route('admin.data.sampah-diserahkan')
            ->with('success', 'Data sampah diserahkan berhasil diperbarui');
    }
}
