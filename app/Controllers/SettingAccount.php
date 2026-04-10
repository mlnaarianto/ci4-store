<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;

class SettingAccount extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Tampilkan halaman setting akun
    public function index()
    {
        $userId = session()->get('user_id');

        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login');
        }

        return view('account/setting', [
            'user' => $user
        ]);
    }

    public function update()
    {
        $userId = session()->get('user_id');

        $rules = [
            'name' => 'required|min_length[3]',
            'nomor_hp' => 'required|numeric|min_length[10]|max_length[15]',
            'alamat' => 'required|min_length[10]',
        ];

        $messages = [
            'name' => [
                'required'    => 'Nama wajib diisi.',
                'min_length'  => 'Nama minimal 3 karakter.',
            ],
            'nomor_hp' => [
                'required'    => 'Nomor HP wajib diisi.',
                'numeric'     => 'Nomor HP hanya boleh berisi angka.',
                'min_length'  => 'Nomor HP minimal 10 digit.',
                'max_length'  => 'Nomor HP maksimal 15 digit.',
            ],
            'alamat' => [
                'required'    => 'Alamat wajib diisi.',
                'min_length'  => 'Alamat minimal 10 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $this->userModel->update($userId, [
            'name'      => $this->request->getPost('name'),
            'nomor_hp'  => $this->request->getPost('nomor_hp'),
            'alamat'    => $this->request->getPost('alamat'),
        ]);

        return redirect()->back()->with('success', 'Profil berhasil disimpan');
    }
}
