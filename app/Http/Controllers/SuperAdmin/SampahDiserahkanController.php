<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SampahDiserahkan;
use App\Models\LokasiAsal;
use App\Models\Jenis;
use App\Models\TujuanSampah;
use App\Models\User;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SampahDiserahkanController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $sampahDiserahkans = SampahDiserahkan::with(['user', 'lokasiAsal', 'jenis', 'tujuanSampah'])
            ->orderBy('tgl', 'desc')
            ->get();
        return view('superAdmin.master.sampah_diserahkan.index', compact('sampahDiserahkans'));
    }

    public function create()
    {
        $users = User::where('role', 3)->get();
        $lokasiAsals = LokasiAsal::all();
        $jenises = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        return view('superAdmin.master.sampah_diserahkan.tambah', compact('users', 'lokasiAsals', 'jenises', 'tujuanSampahs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id',
            'id_jenis' => 'required|exists:jenis,id',
            'id_diserahkan' => 'required|exists:tujuan_sampahs,id',
            'jumlah_berat' => 'required|numeric|min:0'
        ], [
            'tgl.required' => 'Tanggal harus diisi',
            'id_user.required' => 'Petugas harus dipilih',
            'id_lokasi.required' => 'Lokasi asal harus dipilih',
            'id_jenis.required' => 'Jenis sampah harus dipilih',
            'id_diserahkan.required' => 'Tujuan sampah harus dipilih',
            'jumlah_berat.required' => 'Jumlah berat harus diisi',
            'jumlah_berat.numeric' => 'Jumlah berat harus berupa angka',
            'jumlah_berat.min' => 'Jumlah berat minimal 0'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            SampahDiserahkan::create([
                'tgl' => $request->tgl,
                'id_user' => $request->id_user,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'id_diserahkan' => $request->id_diserahkan,
                'jumlah_berat' => $request->jumlah_berat
            ]);

            return $this->createdSuccess('Sampah Diserahkan', 'superadmin.master.sampah-diserahkan');
        } catch (\Exception $e) {
            return $this->createdError('Sampah Diserahkan', $e->getMessage(), 'superadmin.master.sampah-diserahkan');
        }
    }

    public function edit($id)
    {
        $sampahDiserahkan = SampahDiserahkan::findOrFail($id);
        $users = User::where('role', 3)->get();
        $lokasiAsals = LokasiAsal::all();
        $jenises = Jenis::all();
        $tujuanSampahs = TujuanSampah::all();
        return view('superAdmin.master.sampah_diserahkan.edit', compact('sampahDiserahkan', 'users', 'lokasiAsals', 'jenises', 'tujuanSampahs'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'id_user' => 'required|exists:users,id',
            'id_lokasi' => 'required|exists:lokasi_asals,id',
            'id_jenis' => 'required|exists:jenis,id',
            'id_diserahkan' => 'required|exists:tujuan_sampahs,id',
            'jumlah_berat' => 'required|numeric|min:0'
        ], [
            'tgl.required' => 'Tanggal harus diisi',
            'id_user.required' => 'Petugas harus dipilih',
            'id_lokasi.required' => 'Lokasi asal harus dipilih',
            'id_jenis.required' => 'Jenis sampah harus dipilih',
            'id_diserahkan.required' => 'Tujuan sampah harus dipilih',
            'jumlah_berat.required' => 'Jumlah berat harus diisi',
            'jumlah_berat.numeric' => 'Jumlah berat harus berupa angka',
            'jumlah_berat.min' => 'Jumlah berat minimal 0'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $sampahDiserahkan = SampahDiserahkan::findOrFail($id);
            $sampahDiserahkan->update([
                'tgl' => $request->tgl,
                'id_user' => $request->id_user,
                'id_lokasi' => $request->id_lokasi,
                'id_jenis' => $request->id_jenis,
                'id_diserahkan' => $request->id_diserahkan,
                'jumlah_berat' => $request->jumlah_berat
            ]);

            return $this->updatedSuccess('Sampah Diserahkan', 'superadmin.master.sampah-diserahkan');
        } catch (\Exception $e) {
            return $this->updatedError('Sampah Diserahkan', $e->getMessage(), 'superadmin.master.sampah-diserahkan');
        }
    }

    public function destroy($id)
    {
        try {
            $sampahDiserahkan = SampahDiserahkan::findOrFail($id);
            $sampahDiserahkan->delete();

            return $this->deletedSuccess('Sampah Diserahkan');
        } catch (\Exception $e) {
            return $this->deletedError('Sampah Diserahkan', $e->getMessage());
        }
    }
}
