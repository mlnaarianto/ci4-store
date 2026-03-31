<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class StatusAccount extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ✅ Tampilkan halaman status user
    public function index()
    {
        $users = $this->userModel
            ->where('role !=', 'admin') // admin tidak ditampilkan
            ->findAll();

        return view('admin/status/index', [
            'users' => $users
        ]);
    }

    // Nonaktifkan User
    public function nonaktifkan($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        if (session()->get('user_id') == $id) {
            return redirect()->back()->with('error', 'Tidak bisa menonaktifkan akun sendiri');
        }

        if ($user['role'] === 'admin') {
            return redirect()->back()->with('error', 'Tidak bisa menonaktifkan akun admin');
        }

        $this->userModel->update($id, ['status' => 'nonaktif']);

        return redirect()->back()->with('success', 'User berhasil dinonaktifkan');
    }

    // Aktifkan User
    public function aktifkan($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $this->userModel->update($id, ['status' => 'aktif']);

        return redirect()->back()->with('success', 'User berhasil diaktifkan');
    }
}