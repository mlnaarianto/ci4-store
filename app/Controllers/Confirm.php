<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use App\Models\ConfirmModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Confirm extends BaseController
{
    protected $confirmModel;
    protected $pathKtp;
    protected $pathSelfie;
    protected $userModel;

    public function __construct()
{
    $this->confirmModel = new ConfirmModel();
    $this->userModel    = new User(); // 👈 ini yang kurang

    $this->pathKtp    = WRITEPATH . 'uploads/ktp/';
    $this->pathSelfie = WRITEPATH . 'uploads/selfie/';

    if (!is_dir($this->pathKtp)) {
        mkdir($this->pathKtp, 0755, true);
    }

    if (!is_dir($this->pathSelfie)) {
        mkdir($this->pathSelfie, 0755, true);
    }
}

    /*
    ==========================
    INDEX
    ==========================
    */
   public function index()
{
    $userId = session()->get('user_id');
    $status = $this->request->getGet('status');

    $builder = $this->confirmModel->where('user_id', $userId);

    if ($status) {
        $builder->where('status', $status);
    }

    // ambil data user (sesuaikan model kamu)
    $user = $this->userModel->find($userId);

$profileIncomplete = empty($user['alamat']) || empty($user['nomor_hp']);
    return view('confirm/index', [
        'confirms' => $builder->findAll(),
        'status'   => $status,
        'profileIncomplete' => $profileIncomplete
    ]);
}

    public function store()
    {
        $userId = session()->get('user_id');

        $rules = [
            'no_ktp' => [
                'rules' => 'required|numeric|exact_length[16]',
                'errors' => [
                    'required' => 'Nomor KTP wajib diisi',
                    'numeric' => 'Nomor KTP harus angka',
                    'exact_length' => 'Nomor KTP harus 16 digit'
                ]
            ],
            'foto_ktp' => [
                'rules' => 'uploaded[foto_ktp]|is_image[foto_ktp]|mime_in[foto_ktp,image/jpg,image/jpeg,image/png]|max_size[foto_ktp,2048]',
                'errors' => [
                    'uploaded' => 'Foto KTP wajib diupload',
                    'is_image' => 'File KTP harus berupa gambar',
                    'mime_in' => 'Foto KTP hanya boleh JPG atau PNG',
                    'max_size' => 'Ukuran foto KTP maksimal 2MB'
                ]
            ],
            'foto_selfie_ktp' => [
                'rules' => 'uploaded[foto_selfie_ktp]|is_image[foto_selfie_ktp]|mime_in[foto_selfie_ktp,image/jpg,image/jpeg,image/png]|max_size[foto_selfie_ktp,2048]',
                'errors' => [
                    'uploaded' => 'Foto selfie KTP wajib diupload',
                    'is_image' => 'File selfie harus berupa gambar',
                    'mime_in' => 'Foto selfie hanya boleh JPG atau PNG',
                    'max_size' => 'Ukuran foto selfie maksimal 2MB'
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $existing = $this->confirmModel
            ->where('user_id', $userId)
            ->where('status', 'menunggu')
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Masih ada permintaan yang menunggu');
        }

        $fotoKtp    = $this->request->getFile('foto_ktp');
        $fotoSelfie = $this->request->getFile('foto_selfie_ktp');

        $namaKtp    = $fotoKtp->getRandomName();
        $namaSelfie = $fotoSelfie->getRandomName();

        $fotoKtp->move($this->pathKtp, $namaKtp);
        $fotoSelfie->move($this->pathSelfie, $namaSelfie);

        $this->confirmModel->insert([
            'user_id'         => $userId,
            'no_ktp'          => $this->request->getPost('no_ktp'),
            'foto_ktp'        => $namaKtp,
            'foto_selfie_ktp' => $namaSelfie,
            'status'          => 'menunggu'
        ]);

        return redirect()->to('/confirm')->with('success', 'Permintaan berhasil dikirim');
    }

    public function update($id = null)
    {
        $userId  = session()->get('user_id');
        $confirm = $this->confirmModel->find($id);

        if (!$confirm) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($confirm['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Tidak diizinkan');
        }

        if ($confirm['status'] !== 'menunggu') {
            return redirect()->back()->with('error', 'Data sudah diproses');
        }

        /*
    ======================
    CEK FILE
    ======================
    */
        $fileKtp    = $this->request->getFile('foto_ktp');
        $fileSelfie = $this->request->getFile('foto_selfie_ktp');

        $rules = [
            'no_ktp' => [
                'rules' => 'required|numeric|exact_length[16]',
                'errors' => [
                    'required' => 'Nomor KTP wajib diisi',
                    'numeric' => 'Nomor KTP harus angka',
                    'exact_length' => 'Nomor KTP harus 16 digit'
                ]
            ]
        ];

        /*
    ======================
    RULE FOTO KTP
    ======================
    */
        if ($fileKtp && $fileKtp->isValid() && !$fileKtp->hasMoved()) {
            $rules['foto_ktp'] = [
                'rules' => 'uploaded[foto_ktp]|is_image[foto_ktp]|mime_in[foto_ktp,image/jpg,image/jpeg,image/png]|max_size[foto_ktp,2048]',
                'errors' => [
                    'uploaded' => 'Foto KTP gagal diupload',
                    'is_image' => 'File KTP harus berupa gambar',
                    'mime_in' => 'Foto KTP hanya boleh JPG atau PNG',
                    'max_size' => 'Ukuran foto KTP maksimal 2MB'
                ]
            ];
        }

        /*
    ======================
    RULE FOTO SELFIE
    ======================
    */
        if ($fileSelfie && $fileSelfie->isValid() && !$fileSelfie->hasMoved()) {
            $rules['foto_selfie_ktp'] = [
                'rules' => 'uploaded[foto_selfie_ktp]|is_image[foto_selfie_ktp]|mime_in[foto_selfie_ktp,image/jpg,image/jpeg,image/png]|max_size[foto_selfie_ktp,2048]',
                'errors' => [
                    'uploaded' => 'Foto selfie gagal diupload',
                    'is_image' => 'File selfie harus berupa gambar',
                    'mime_in' => 'Foto selfie hanya boleh JPG atau PNG',
                    'max_size' => 'Ukuran foto selfie maksimal 2MB'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        /*
    ======================
    DATA UPDATE
    ======================
    */
        $dataUpdate = [
            'no_ktp' => $this->request->getPost('no_ktp')
        ];

        /*
    ======================
    UPDATE FOTO KTP
    ======================
    */
        if ($fileKtp && $fileKtp->isValid() && !$fileKtp->hasMoved()) {

            if (!empty($confirm['foto_ktp']) && file_exists($this->pathKtp . $confirm['foto_ktp'])) {
                unlink($this->pathKtp . $confirm['foto_ktp']);
            }

            $namaKtp = $fileKtp->getRandomName();
            $fileKtp->move($this->pathKtp, $namaKtp);

            $dataUpdate['foto_ktp'] = $namaKtp;
        }

        /*
    ======================
    UPDATE FOTO SELFIE
    ======================
    */
        if ($fileSelfie && $fileSelfie->isValid() && !$fileSelfie->hasMoved()) {

            if (!empty($confirm['foto_selfie_ktp']) && file_exists($this->pathSelfie . $confirm['foto_selfie_ktp'])) {
                unlink($this->pathSelfie . $confirm['foto_selfie_ktp']);
            }

            $namaSelfie = $fileSelfie->getRandomName();
            $fileSelfie->move($this->pathSelfie, $namaSelfie);

            $dataUpdate['foto_selfie_ktp'] = $namaSelfie;
        }

        $this->confirmModel->update($id, $dataUpdate);

        return redirect()->to('/confirm')->with('success', 'Data berhasil diperbarui');
    }

    /*
    ==========================
    DELETE
    ==========================
    */
    public function delete($id = null)
    {
        $userId  = session()->get('user_id');
        $confirm = $this->confirmModel->find($id);

        if (!$confirm) {
            return $this->response->setJSON(['message' => 'Data tidak ditemukan'])->setStatusCode(404);
        }

        if ($confirm['user_id'] != $userId) {
            return $this->response->setJSON(['message' => 'Tidak diizinkan'])->setStatusCode(403);
        }

        if ($confirm['status'] !== 'menunggu') {
            return $this->response->setJSON(['message' => 'Tidak bisa dihapus'])->setStatusCode(400);
        }

        if (!empty($confirm['foto_ktp']) && file_exists($this->pathKtp . $confirm['foto_ktp'])) {
            unlink($this->pathKtp . $confirm['foto_ktp']);
        }

        if (!empty($confirm['foto_selfie_ktp']) && file_exists($this->pathSelfie . $confirm['foto_selfie_ktp'])) {
            unlink($this->pathSelfie . $confirm['foto_selfie_ktp']);
        }

        $this->confirmModel->delete($id);

        return redirect()->to('/confirm')->with('success', 'Data berhasil dihapus');
    }

    /*
    ==========================
    IMAGE PROXY (AMAN)
    ==========================
    */
    public function image($type, $filename)
    {
        if (!in_array($type, ['ktp', 'selfie'])) {
            throw PageNotFoundException::forPageNotFound();
        }

        $path = WRITEPATH . 'uploads/' . $type . '/' . $filename;

        if (!file_exists($path)) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($path))
            ->setBody(file_get_contents($path));
    }
}
