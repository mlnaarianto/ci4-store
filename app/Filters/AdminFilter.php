<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Harus login dulu
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek role admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')
                ->with('error', 'Akses hanya untuk admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}