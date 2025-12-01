<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\WithSweetAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use WithSweetAlert;

    public function index()
    {
        $users = User::orderBy('name', 'asc')->get();
        return view('superAdmin.master.users.index', compact('users'));
    }

    public function create()
    {
        return view('superAdmin.master.users.tambah');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:1,2,3'
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            return $this->createdSuccess('User', 'superadmin.master.users');
        } catch (\Exception $e) {
            return $this->createdError('User', $e->getMessage(), 'superadmin.master.users');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('superAdmin.master.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:1,2,3'
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator->errors());
        }

        try {
            $user = User::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return $this->updatedSuccess('User', 'superadmin.master.users');
        } catch (\Exception $e) {
            return $this->updatedError('User', $e->getMessage(), 'superadmin.master.users');
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Cek jika user adalah diri sendiri
            if ($user->id == auth()->id()) {
                return $this->deletedError('User', 'Tidak dapat menghapus akun sendiri');
            }

            $user->delete();

            return $this->deletedSuccess('User');
        } catch (\Exception $e) {
            return $this->deletedError('User', $e->getMessage());
        }
    }
}
