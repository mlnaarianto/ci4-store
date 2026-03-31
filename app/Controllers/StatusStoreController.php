<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;

class StatusStoreController extends BaseController
{
    protected $storeModel;

    public function __construct()
    {
        $this->storeModel = new StoreModel();
    }

    /**
     * List semua toko (admin)
     */
    public function index()
    {
        $stores = $this->storeModel
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return view('admin/StoreStatus/index', [
            'stores' => $stores
        ]);
    }

    /**
     * Form edit status
     */
    public function edit($id)
    {
        $store = $this->storeModel->find($id);

        if (!$store) {
            return redirect()->back()->with('error', 'Store tidak ditemukan');
        }

        return view('admin/stores/edit_status', [
            'store' => $store
        ]);
    }

    /**
     * Update status (pending / aktif / ditolak)
     */
    public function update($id)
    {
        $store = $this->storeModel->find($id);

        if (!$store) {
            return redirect()->back()->with('error', 'Store tidak ditemukan');
        }

        // Validasi dasar
        $rules = [
            'status' => 'required|in_list[pending,aktif,ditolak]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $newStatus = $this->request->getPost('status');

        $data = [
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Kalau ditolak → wajib isi alasan
        if ($newStatus === 'ditolak') {
            $alasan = $this->request->getPost('alasan');

            if (empty($alasan)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Alasan penolakan wajib diisi');
            }

            $data['alasan'] = $alasan;
        } else {
            // selain ditolak → kosongkan alasan
            $data['alasan'] = null;
        }

        if ($this->storeModel->update($id, $data)) {
            return redirect()->to('/admin/stores')
                ->with('success', 'Status toko berhasil diperbarui');
        } else {
            return redirect()->back()
                ->with('error', 'Gagal update status toko');
        }
    }

    /**
     * Approve cepat
     */
    public function approve($id)
    {
        $store = $this->storeModel->find($id);

        if (!$store) {
            return redirect()->back()->with('error', 'Store tidak ditemukan');
        }

        $this->storeModel->update($id, [
            'status' => 'aktif',
            'alasan' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/stores')
            ->with('success', 'Toko berhasil diaktifkan');
    }

    /**
     * Reject cepat (butuh alasan)
     */
    public function reject($id)
    {
        $store = $this->storeModel->find($id);

        if (!$store) {
            return redirect()->back()->with('error', 'Store tidak ditemukan');
        }

        // Validasi alasan
        $rules = [
            'alasan' => 'required|min_length[5]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Alasan penolakan wajib diisi (min 5 karakter)');
        }

        $this->storeModel->update($id, [
            'status' => 'ditolak',
            'alasan' => $this->request->getPost('alasan'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/stores')
            ->with('success', 'Toko berhasil ditolak');
    }
}