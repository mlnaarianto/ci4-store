<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ProfileComplete implements FilterInterface
{
    /**
     * Dijalankan sebelum controller
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan helper tersedia
        helper('user');

        // Cek profil lengkap
        if (!is_profile_complete()) {

            // Simpan pesan ke flashdata
            session()->setFlashdata(
                'error',
                'Lengkapi profil terlebih dahulu sebelum melanjutkan.'
            );

            // Redirect ke halaman setting akun
            return redirect()->to('/account/setting');
        }
    }

    /**
     * Dijalankan setelah controller (tidak dipakai)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi apa pun
    }
}