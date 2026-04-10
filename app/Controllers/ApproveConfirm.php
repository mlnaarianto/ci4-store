<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ConfirmModel;
use App\Models\User;

class ApproveConfirm extends BaseController
{
    protected $confirmModel;
    protected $userModel;

    public function __construct()
    {
        $this->confirmModel = new ConfirmModel();
        $this->userModel    = new User();
    }

    /*
    ==========================
    LIST DATA
    ==========================
    */
    public function index()
    {
        $status = $this->request->getGet('status');

        $builder = $this->confirmModel;

        if ($status) {
            $builder = $builder->where('status', $status);
        }

        return view('admin/confirm/index', [
            'confirms' => $builder->orderBy('created_at', 'asc')->findAll(),
            'status'   => $status
        ]);
    }

    /*
    ==========================
    APPROVE (SETUJUI)
    ==========================
    */
    public function approve($id)
    {
        $confirm = $this->confirmModel->find($id);

        if (!$confirm) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Sudah diproses?
        if ($confirm['status'] !== 'menunggu') {
            return redirect()->back()->with('error', 'Permintaan sudah diproses');
        }

        // Ambil user
        $user = $this->userModel->find($confirm['user_id']);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        /*
        ===================================
        TRANSACTION (SUPER PENTING)
        ===================================
        Supaya:
        - status disetujui
        - role berubah
        harus berhasil bersamaan
        */
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Update status confirm
        $this->confirmModel->update($id, [
            'status' => 'disetujui'
        ]);

        // 2. Update role hanya jika masih pembeli
        if ($user['role'] === 'pembeli') {
            $this->userModel->update($confirm['user_id'], [
                'role' => 'penjual'
            ]);
        }

        $db->transComplete();

        // Kalau gagal
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyetujui permintaan');
        }

        return redirect()->back()->with('success', 'Permintaan disetujui. User sekarang menjadi PENJUAL');
    }

    /*
    ==========================
    REJECT (TOLAK)
    ==========================
    */
    public function reject($id)
    {
        $confirm = $this->confirmModel->find($id);

        if (!$confirm) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($confirm['status'] !== 'menunggu') {
            return redirect()->back()->with('error', 'Permintaan sudah diproses');
        }

        $this->confirmModel->update($id, [
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Permintaan ditolak');
    }
}