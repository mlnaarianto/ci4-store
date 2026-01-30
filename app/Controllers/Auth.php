<?php

namespace App\Controllers;

use App\Models\User;
use CodeIgniter\Controller;
use App\Libraries\GoogleAuth;

class Auth extends Controller
{
    /* ================= LOGIN PAGE ================= */
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        return view('auth/login', [
            'next' => $this->request->getGet('next')
        ]);
    }

    /* ================= REGISTER PAGE ================= */
    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        return view('auth/register', [
            'next' => $this->request->getGet('next')
        ]);
    }

    /* ================= REGISTER PROCESS ================= */
    public function storeRegister()
    {
        $userModel = new User();

        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ];

        $userModel->save($data);

        $next = $this->request->getPost('next');
        $message = 'Registrasi berhasil! Silakan login.';

        if ($next && str_starts_with($next, '/')) {
            return redirect()->to('/login?next=' . urlencode($next))->with('success', $message);
        }

        return redirect()->to('/login')->with('success', $message);
    }

    /* ================= LOGIN PROCESS ================= */
    public function attemptLogin()
    {
        $userModel = new User();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $next     = $this->request->getPost('next');

        $user = $userModel->getByEmail($email);

        // ❌ User tidak ada
        if (!$user) {
            return $this->loginError('Email tidak ditemukan.');
        }

        // ❌ Akun Google tidak punya password
        if ($user['password'] === null) {
            return $this->loginError('Akun ini terdaftar via Google.');
        }

        // ❌ Password salah
        if (!password_verify($password, $user['password'])) {
            return $this->loginError('Password salah.');
        }

        // ✅ LOGIN SUKSES
        session()->set([
            'user_id'     => $user['id'],
            'user_name'   => $user['name'],
            'user_email'  => $user['email'],
            'user_avatar' => $user['avatar_url'] ?? null,
            'logged_in'   => true,
        ]);

        $redirectUrl = ($next && str_starts_with($next, '/')) ? $next : '/';

        // Jika request dari AJAX (modal)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'   => 'success',
                'redirect' => $redirectUrl
            ]);
        }

        return redirect()->to($redirectUrl);
    }

    /* ================= GOOGLE LOGIN ================= */
    public function google()
    {
        return redirect()->to(GoogleAuth::getLoginUrl());
    }

    /* ================= GOOGLE CALLBACK ================= */
    public function googleCallback()
    {
        $state = $this->request->getGet('state');

        if (!GoogleAuth::validateState($state)) {
            return redirect()->to('/login')->with('error', 'Invalid OAuth state');
        }

        try {
            $googleUser = GoogleAuth::getUser($this->request->getGet('code'));

            $userModel = new User();
            $user = $userModel->where('email', $googleUser['email'])->first();

            $tokenExpire = date('Y-m-d H:i:s', $googleUser['expires']);

            if ($user) {
                // Update user lama
                $userModel->update($user['id'], [
                    'google_id'        => $googleUser['id'],
                    'avatar_url'       => $googleUser['avatar'],
                    'token'            => $googleUser['access_token'],
                    'token_expired_at' => $tokenExpire,
                ]);
            } else {
                // User baru Google
                $userModel->save([
                    'name'             => $googleUser['name'],
                    'email'            => $googleUser['email'],
                    'google_id'        => $googleUser['id'],
                    'avatar_url'       => $googleUser['avatar'],
                    'password'         => null,
                    'token'            => $googleUser['access_token'],
                    'token_expired_at' => $tokenExpire,
                ]);

                $user = $userModel->where('email', $googleUser['email'])->first();
            }

            // 🔥 SET SESSION TERMASUK AVATAR
            session()->set([
                'user_id'     => $user['id'],
                'user_name'   => $user['name'],
                'user_email'  => $user['email'],
                'user_avatar' => $googleUser['avatar'],
                'logged_in'   => true,
            ]);

            return redirect()->to('/');
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', 'Login Google gagal.');
        }
    }

    /* ================= LOGOUT ================= */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Anda telah logout.');
    }

    /* ================= HELPER ERROR ================= */
    private function loginError($message)
    {
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $message
            ]);
        }

        return redirect()->back()->withInput()->with('error', $message);
    }
}
