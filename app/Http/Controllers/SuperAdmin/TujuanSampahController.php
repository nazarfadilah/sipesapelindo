<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\TujuanSampah;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TujuanSampahController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $tujuanSampahs = TujuanSampah::orderBy('nama_tujuan', 'asc')->get();
        return view('superAdmin.master.tujuan.index', compact('tujuanSampahs'));
    }

    public function create()
    {
        return view('superAdmin.master.tujuan.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan'
        ], [
            'nama_tujuan.required' => 'Nama tujuan sampah harus diisi',
            'nama_tujuan.unique' => 'Nama tujuan sampah sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            TujuanSampah::create([
                'nama_tujuan' => $request->nama_tujuan
            ]);

            return $this->createdSuccess('Tujuan Sampah', 'superadmin.master.tujuan-sampah');
        } catch (\Exception $e) {
            return $this->createdError('Tujuan Sampah', $e->getMessage(), 'superadmin.master.tujuan-sampah');
        }
    }

    public function edit($id)
    {
        $tujuanSampah = TujuanSampah::findOrFail($id);
        return view('superAdmin.master.tujuan.edit', compact('tujuanSampah'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_tujuan' => 'required|string|max:255|unique:tujuan_sampahs,nama_tujuan,' . $id
        ], [
            'nama_tujuan.required' => 'Nama tujuan sampah harus diisi',
            'nama_tujuan.unique' => 'Nama tujuan sampah sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $tujuanSampah = TujuanSampah::findOrFail($id);
            $tujuanSampah->update([
                'nama_tujuan' => $request->nama_tujuan
            ]);

            return $this->updatedSuccess('Tujuan Sampah', 'superadmin.master.tujuan-sampah');
        } catch (\Exception $e) {
            return $this->updatedError('Tujuan Sampah', $e->getMessage(), 'superadmin.master.tujuan-sampah');
        }
    }

    public function destroy($id)
    {
        try {
            $tujuanSampah = TujuanSampah::findOrFail($id);
            $tujuanSampah->delete();

            return $this->deletedSuccess('Tujuan Sampah');
        } catch (\Exception $e) {
            return $this->deletedError('Tujuan Sampah', $e->getMessage());
        }
    }
}
