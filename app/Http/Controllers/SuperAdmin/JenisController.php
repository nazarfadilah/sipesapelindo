<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Jenis;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $jenises = Jenis::orderBy('nama_jenis', 'asc')->get();
        return view('superAdmin.master.jenis.index', compact('jenises'));
    }

    public function create()
    {
        return view('superAdmin.master.jenis.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis'
        ], [
            'nama_jenis.required' => 'Nama jenis sampah harus diisi',
            'nama_jenis.unique' => 'Nama jenis sampah sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            Jenis::create([
                'nama_jenis' => $request->nama_jenis
            ]);

            return $this->createdSuccess('Jenis Sampah', 'superadmin.master.jenis-sampah');
        } catch (\Exception $e) {
            return $this->createdError('Jenis Sampah', $e->getMessage(), 'superadmin.master.jenis-sampah');
        }
    }

    public function edit($id)
    {
        $jenis = Jenis::findOrFail($id);
        return view('superAdmin.master.jenis.edit', compact('jenis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis,' . $id
        ], [
            'nama_jenis.required' => 'Nama jenis sampah harus diisi',
            'nama_jenis.unique' => 'Nama jenis sampah sudah ada'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $jenis = Jenis::findOrFail($id);
            $jenis->update([
                'nama_jenis' => $request->nama_jenis
            ]);

            return $this->updatedSuccess('Jenis Sampah', 'superadmin.master.jenis-sampah');
        } catch (\Exception $e) {
            return $this->updatedError('Jenis Sampah', $e->getMessage(), 'superadmin.master.jenis-sampah');
        }
    }

    public function destroy($id)
    {
        try {
            $jenis = Jenis::findOrFail($id);
            $jenis->delete();

            return $this->deletedSuccess('Jenis Sampah');
        } catch (\Exception $e) {
            return $this->deletedError('Jenis Sampah', $e->getMessage());
        }
    }
}
