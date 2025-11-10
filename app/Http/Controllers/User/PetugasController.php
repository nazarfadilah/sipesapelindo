<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SampahTerkelola;
use App\Models\SampahDiserahkan;
use App\Models\Jenis;
use App\Models\LokasiAsal;
use App\Models\TujuanSampah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    /**
     * Menampilkan halaman dashboard petugas
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        return view('petugas.dashboard');
    }

    /**
     * Menampilkan daftar sampah terkelola
     *
     * @return \Illuminate\View\View
     */
    public function sampahTerkelola()
    {
        $sampahTerkelolas = SampahTerkelola::with(['user', 'lokasiAsal', 'jenis'])
            ->orderBy('tgl', 'desc')
            ->get();
        
        return view('petugas.sampah_terkelola', compact('sampahTerkelolas'));
    }
    
    /**
     * Menampilkan daftar sampah diserahkan
     *
     * @return \Illuminate\View\View
     */
    public function sampahDiserahkan()
    {
        $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl', 'desc')
            ->get();
        
        return view('petugas.sampah_diserahkan', compact('sampahDiserahkans'));
    }
    
    /**
     * Menampilkan form input sampah terkelola
     *
     * @return \Illuminate\View\View
     */
    public function inputSampahTerkelola()
    {
        $lokasiAsals = LokasiAsal::all();
        $jenisAll = Jenis::all();
        
        return view('petugas.input_sampah_terkelola', compact('lokasiAsals', 'jenisAll'));
    }
    
    /**
     * Menyimpan data sampah terkelola baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSampahTerkelola(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id',
            'id_jenis' => 'required|exists:jenis,id',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan file ke storage/app/public/foto-sampah
            $file->move(storage_path('app/public/foto-sampah'), $filename);
            $validatedData['foto'] = 'foto-sampah/' . $filename;
        }
        
        SampahTerkelola::create($validatedData);
        
        return redirect()->route('petugas.sampah-terkelola')->with('success', 'Data sampah terkelola berhasil disimpan.');
    }
    
    /**
     * Menampilkan form input sampah diserahkan
     *
     * @return \Illuminate\View\View
     */
    public function inputSampahDiserahkan()
    {
        $lokasiAsals = LokasiAsal::all();
        $jenisAll = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        
        return view('petugas.input_sampah_diserahkan', compact('lokasiAsals', 'jenisAll', 'tujuanSampahs'));
    }
    
    /**
     * Menyimpan data sampah diserahkan baru
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSampahDiserahkan(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id',
            'id_jenis' => 'required|exists:jenis,id',
            'jumlah_berat' => 'required|numeric|min:0',
            'tgl' => 'required|date',
            'id_diserahkan' => 'required|exists:tujuan_sampahs,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan file ke storage/app/public/foto-sampah
            $file->move(storage_path('app/public/foto-sampah'), $filename);
            $validatedData['foto'] = 'foto-sampah/' . $filename;
        }
        
        SampahDiserahkan::create($validatedData);
        
        return redirect()->route('petugas.sampah-diserahkan')->with('success', 'Data sampah diserahkan berhasil disimpan.');
    }
}
