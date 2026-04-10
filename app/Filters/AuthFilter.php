<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(
                '/login?next=' . urlencode('/' . uri_string())
            );
        }

        // 🔥 CEK STATUS USER
        $userModel = new User();
        $user = $userModel->find(session()->get('user_id'));

        if (!$user || $user['status'] === 'nonaktif') {

            session()->destroy();

            return redirect()->to('/login')
                ->with('error', 'Akun anda telah dinonaktifkan oleh admin');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}