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
            return $this->redirectByRole();
        }

        return view('auth/login');
    }

    /* ================= REGISTER PAGE ================= */
    public function register()
    {
        if (session()->get('logged_in')) {
            return $this->redirectByRole();
        }

        return view('auth/register');
    }

    /* ================= REGISTER PROCESS ================= */
    public function storeRegister()
    {
        $userModel = new User();

        $userModel->save([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'     => 'pembeli',
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /* ================= LOGIN PROCESS ================= */
    public function attemptLogin()
    {
        $userModel = new User();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return $this->loginError('Email tidak ditemukan.');
        }

        if ($user['password'] === null) {
            return $this->loginError('Akun ini terdaftar via Google.');
        }

        if (!password_verify($password, $user['password'])) {
            return $this->loginError('Password salah.');
        }

        // 🔥 TAMBAHKAN INI
        if (isset($user['status']) && $user['status'] === 'nonaktif') {
            return $this->loginError('Akun anda sedang dinonaktifkan oleh admin.');
        }

        $this->setUserSession($user);

        return $this->redirectByRole();
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
            return redirect()->to('/login')
                ->with('error', 'Invalid OAuth state');
        }

        try {

            $googleUser = GoogleAuth::getUser(
                $this->request->getGet('code')
            );

            $userModel = new User();

            // cek user berdasarkan email
            $user = $userModel
                ->where('email', $googleUser['email'])
                ->first();

            // 🔥 CEK STATUS USER
            if ($user && isset($user['status']) && $user['status'] === 'nonaktif') {
                return redirect()->to('/login')
                    ->with('error', 'Akun anda sedang dinonaktifkan oleh admin.');
            }

            // 🔥 SIMPAN AVATAR KE LOKAL
            $localAvatar = $this->saveGoogleAvatar(
                $googleUser['avatar'],
                $googleUser['email']
            );

            // jika gagal, biarkan null (jangan fallback ke Google)
            if (!$localAvatar) {
                log_message('error', 'Gagal menyimpan avatar Google: ' . $googleUser['email']);
            }

            $tokenExpire = date(
                'Y-m-d H:i:s',
                $googleUser['expires']
            );

            // ================= UPDATE / INSERT USER =================
            if ($user) {

                // 🧹 hapus avatar lama jika lokal
                if (!empty($user['avatar_url']) && strpos($user['avatar_url'], 'uploads/') === 0) {

                    $oldAvatar = WRITEPATH . $user['avatar_url'];

                    if (file_exists($oldAvatar)) {
                        @unlink($oldAvatar);
                    }
                }

                // 🔥 UPDATE USER (WAJIB LOCAL AVATAR)
                $userModel->update($user['id'], [
                    'google_id'        => $googleUser['id'],
                    'avatar_url'       => $localAvatar, // ✅ FIX: tidak pakai fallback Google
                    'token'            => $googleUser['access_token'],
                    'token_expired_at' => $tokenExpire,
                ]);
            } else {

                // 🆕 INSERT USER BARU
                $userModel->save([
                    'name'             => $googleUser['name'],
                    'email'            => $googleUser['email'],
                    'google_id'        => $googleUser['id'],
                    'avatar_url'       => $localAvatar, // ✅ FIX
                    'password'         => null,
                    'role'             => 'pembeli',
                    'status'           => 'aktif',
                    'token'            => $googleUser['access_token'],
                    'token_expired_at' => $tokenExpire,
                ]);
            }

            // 🔥 PENTING BANGET: AMBIL DATA TERBARU DARI DB
            $user = $userModel
                ->where('email', $googleUser['email'])
                ->first();

            // 🔐 SET SESSION
            $this->setUserSession($user);

            return $this->redirectByRole();
        } catch (\Exception $e) {

            log_message('error', 'Google login error: ' . $e->getMessage());

            return redirect()->to('/login')
                ->with('error', 'Login Google gagal.');
        }
    }
    /* ================= SET SESSION ================= */
    private function setUserSession(array $user)
    {
        session()->set([
            'user_id'     => $user['id'],
            'user_name'   => $user['name'],
            'user_email'  => $user['email'],
            'user_avatar' => $user['avatar_url'] ?? null,
            'role'   => $user['role'] ?? 'pembeli',
            'logged_in'   => true,
        ]);
    }

    /* ================= REDIRECT BY ROLE ================= */
    private function redirectByRole()
    {
        if (session()->get('role') === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/');
    }

    /* ================= SAVE GOOGLE AVATAR ================= */
    private function saveGoogleAvatar(string $avatarUrl, string $email): ?string
    {
        try {

            $client = \Config\Services::curlrequest();

            $response = $client->get($avatarUrl, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'Accept'     => 'image/*'
                ],
                'timeout' => 10
            ]);

            // ❌ gagal request
            if ($response->getStatusCode() !== 200) {
                return null;
            }

            $imageData = $response->getBody();

            // ❌ kalau terlalu kecil (biasanya error HTML)
            if (strlen($imageData) < 1000) {
                return null;
            }

            // 🔍 deteksi mime type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_buffer($finfo, $imageData);
            finfo_close($finfo);

            // validasi mime
            $allowedMime = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

            if (!isset($allowedMime[$mime])) {
                return null;
            }

            $extension = $allowedMime[$mime];

            // 🧾 nama file unik
            $fileName = 'avatar_' . md5($email . time()) . '.' . $extension;

            // 📁 path
            $relativePath = 'uploads/avatars/' . $fileName;
            $fullPath     = WRITEPATH . $relativePath;

            // 📂 buat folder jika belum ada
            if (!is_dir(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0777, true);
            }

            // 💾 simpan file
            file_put_contents($fullPath, $imageData);

            return $relativePath;
        } catch (\Throwable $e) {

            log_message('error', 'Avatar save error: ' . $e->getMessage());

            return null;
        }
    }

    /* ================= SERVE AVATAR ================= */
    public function serveAvatar($filename = null)
    {
        if (!$filename) {
            return $this->response->setStatusCode(404);
        }

        $filePath = WRITEPATH . 'uploads/avatars/' . basename($filename);

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404);
        }

        $mimeType = mime_content_type($filePath);
        $fileSize = filesize($filePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Length', $fileSize)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setHeader('Expires', gmdate("D, d M Y H:i:s", time() + 86400) . " GMT")
            ->setBody(file_get_contents($filePath));
    }

    /* ================= LOGOUT ================= */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')
            ->with('success', 'Anda telah logout.');
    }

    /* ================= ERROR HELPER ================= */
    private function loginError($message)
    {
        return redirect()->back()
            ->withInput()
            ->with('error', $message);
    }
}
