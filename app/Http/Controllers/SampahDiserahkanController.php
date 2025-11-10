<?php

namespace App\Http\Controllers;

use App\Models\SampahDiserahkan;
use App\Models\User;
use App\Models\Lokasi; // Sesuaikan dengan nama model Anda
use App\Models\JenisSampah; // Sesuaikan dengan nama model Anda
use App\Models\TujuanSampah; // Sesuaikan dengan nama model Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SampahDiserahkanController extends Controller
{
    /**
     * Menampilkan daftar semua data sampah yang diserahkan.
     */
    public function index()
    {
        $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasi', 'jenisSampah', 'tujuanSampah'])->latest()->get();
        return view('sampah_diserahkan.index', ['sampahDiserahkans' => $sampahDiserahkans]);
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        // Data untuk mengisi dropdown di form
        $users = User::all();
        $lokasis = Lokasi::all();
        $jenisSampahs = JenisSampah::all();
        $tujuanSampahs = TujuanSampah::all();

        return view('sampah_diserahkan.create', compact('users', 'lokasis', 'jenisSampahs', 'tujuanSampahs'));
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasis,id',
            'id_jenis' => 'required|exists:jenis_sampahs,id',
            'id_diserahkan' => 'required|exists:tujuan_sampahs,id',
            'jumlah_berat' => 'required|integer|min:0',
            'tgl' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/foto-diserahkan');
            $validatedData['foto'] = $path;
        }

        SampahDiserahkan::create($validatedData);

        return redirect()->route('sampah-diserahkan.index')->with('success', 'Data Sampah Diserahkan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data.
     */
    public function edit(SampahDiserahkan $sampahDiserahkan)
    {
        $users = User::all();
        $lokasis = Lokasi::all();
        $jenisSampahs = JenisSampah::all();
        $tujuanSampahs = TujuanSampah::all();

        return view('sampah_diserahkan.edit', compact('sampahDiserahkan', 'users', 'lokasis', 'jenisSampahs', 'tujuanSampahs'));
    }

    /**
     * Mengupdate data di database.
     */
    public function update(Request $request, SampahDiserahkan $sampahDiserahkan)
    {
        $validatedData = $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasis,id',
            'id_jenis' => 'required|exists:jenis_sampahs,id',
            'id_diserahkan' => 'required|exists:tujuan_sampahs,id',
            'jumlah_berat' => 'required|integer|min:0',
            'tgl' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($sampahDiserahkan->foto) {
                Storage::delete($sampahDiserahkan->foto);
            }
            $path = $request->file('foto')->store('public/foto-diserahkan');
            $validatedData['foto'] = $path;
        }

        $sampahDiserahkan->update($validatedData);

        return redirect()->route('sampah-diserahkan.index')->with('success', 'Data Sampah Diserahkan berhasil diperbarui.');
    }

    /**
     * Menghapus data dari database.
     */
    public function destroy(SampahDiserahkan $sampahDiserahkan)
    {
        if ($sampahDiserahkan->foto) {
            Storage::delete($sampahDiserahkan->foto);
        }
        $sampahDiserahkan->delete();

        return redirect()->route('sampah-diserahkan.index')->with('success', 'Data Sampah Diserahkan berhasil dihapus.');
    }
}