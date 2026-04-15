<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\ProductModel; // tambahin di atas

class Store extends BaseController
{
    protected $storeModel;
    protected $pathLogo;
    protected $pathBanner;

    public function __construct()
    {
        $this->storeModel = new StoreModel();

        $this->pathLogo   = WRITEPATH . 'uploads/stores/logo/';
        $this->pathBanner = WRITEPATH . 'uploads/stores/banner/';

        if (!is_dir($this->pathLogo)) {
            mkdir($this->pathLogo, 0755, true);
        }

        if (!is_dir($this->pathBanner)) {
            mkdir($this->pathBanner, 0755, true);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $userId = session()->get('user_id');

        $store = $this->storeModel
            ->where('user_id', $userId)
            ->first();

        $products = [];

        if ($store) {
            $productModel = new ProductModel();

            $products = $productModel
                ->where('store_id', $store['id'])
                ->findAll();
        }

        return view('store/index', [
            'store' => $store,
            'products' => $products
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('store/create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store()
    {
        $userId = session()->get('user_id');

        if ($this->storeModel->where('user_id', $userId)->first()) {
            return redirect()->back()->with('error', 'Anda sudah memiliki toko.');
        }

        $rules = [
            'nama_toko' => 'required|min_length[3]|max_length[150]',
            'logo'      => 'uploaded[logo]|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]|max_size[logo,2048]',
            'banner'    => 'permit_empty|is_image[banner]|mime_in[banner,image/jpg,image/jpeg,image/png]|max_size[banner,4096]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $logoFile   = $this->request->getFile('logo');
        $bannerFile = $this->request->getFile('banner');

        $namaLogo   = null;
        $namaBanner = null;

        // Upload Logo
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
            $namaLogo = $logoFile->getRandomName();
            $logoFile->move($this->pathLogo, $namaLogo);
        }

        // Upload Banner
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {
            $namaBanner = $bannerFile->getRandomName();
            $bannerFile->move($this->pathBanner, $namaBanner);
        }

        $this->storeModel->insert([
            'user_id'   => $userId,
            'nama_toko' => $this->request->getPost('nama_toko'),
            'slug'      => url_title($this->request->getPost('nama_toko'), '-', true),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'alamat'    => $this->request->getPost('alamat'),
            'logo'      => $namaLogo,
            'banner'    => $namaBanner,
            'status'    => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/store')->with('success', 'Toko berhasil dibuat.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $userId = session()->get('user_id');
        $store  = $this->storeModel->find($id);

        if (!$store || $store['user_id'] != $userId) {
            return redirect()->to('/store')->with('error', 'Tidak diizinkan.');
        }

        return view('store/edit', [
            'store' => $store
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update($id)
    {
        $userId = session()->get('user_id');
        $store  = $this->storeModel->find($id);

        if (!$store || $store['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Tidak diizinkan.');
        }

        $rules = [
            'nama_toko' => 'required|min_length[3]|max_length[150]',
            'logo'      => 'permit_empty|is_image[logo]|mime_in[logo,image/jpg,image/jpeg,image/png]|max_size[logo,2048]',
            'banner'    => 'permit_empty|is_image[banner]|mime_in[banner,image/jpg,image/jpeg,image/png]|max_size[banner,4096]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $dataUpdate = [
            'nama_toko'  => $this->request->getPost('nama_toko'),
            'slug'       => url_title($this->request->getPost('nama_toko'), '-', true),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'alamat'     => $this->request->getPost('alamat'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $logoFile   = $this->request->getFile('logo');
        $bannerFile = $this->request->getFile('banner');

        /*
    |--------------------------------------------------------------------------
    | UPDATE LOGO
    |--------------------------------------------------------------------------
    */
        if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {

            $namaLogo = $logoFile->getRandomName();

            if ($logoFile->move($this->pathLogo, $namaLogo)) {

                // hapus logo lama
                if (!empty($store['logo'])) {
                    $oldLogoPath = $this->pathLogo . $store['logo'];
                    if (file_exists($oldLogoPath) && is_file($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }

                $dataUpdate['logo'] = $namaLogo;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | UPDATE BANNER
    |--------------------------------------------------------------------------
    */
        if ($bannerFile && $bannerFile->isValid() && !$bannerFile->hasMoved()) {

            $namaBanner = $bannerFile->getRandomName();

            if ($bannerFile->move($this->pathBanner, $namaBanner)) {

                // hapus banner lama
                if (!empty($store['banner'])) {
                    $oldBannerPath = $this->pathBanner . $store['banner'];
                    if (file_exists($oldBannerPath) && is_file($oldBannerPath)) {
                        unlink($oldBannerPath);
                    }
                }

                $dataUpdate['banner'] = $namaBanner;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */
        $this->storeModel->update($id, $dataUpdate);

        return redirect()->to('/store')->with('success', 'Toko berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | IMAGE LOADER (dengan cache)
    |--------------------------------------------------------------------------
    */
    public function image($type, $filename)
    {
        // Sanitasi filename untuk keamanan
        $filename = basename($filename);

        // Tentukan path berdasarkan type
        $basePath = match ($type) {
            'logo'   => $this->pathLogo,
            'banner' => $this->pathBanner,
            default  => throw PageNotFoundException::forPageNotFound(),
        };

        $path = $basePath . $filename;

        // Cek apakah file exists
        if (!file_exists($path)) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Dapatkan mime type
        $mime = mime_content_type($path);

        // Set header dengan cache (1 hari)
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setHeader('Last-Modified', gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT')
            ->setBody(file_get_contents($path));
    }

    /*
    |--------------------------------------------------------------------------
    | REFRESH IMAGE (tanpa cache)
    |--------------------------------------------------------------------------
    | Method ini khusus untuk memaksa refresh gambar dengan header no-cache
    | Dipanggil dari tombol refresh di view
    */
    public function refreshImage($type, $filename)
    {
        // Force refresh dengan mengirimkan header no-cache
        $filename = basename($filename);

        $basePath = match ($type) {
            'logo'   => $this->pathLogo,
            'banner' => $this->pathBanner,
            default  => throw PageNotFoundException::forPageNotFound(),
        };

        $path = $basePath . $filename;

        if (!file_exists($path)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);

        // Header untuk mencegah cache
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', 'Wed, 11 Jan 1984 05:00:00 GMT')
            ->setBody(file_get_contents($path));
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE STORE (jika diperlukan)
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $userId = session()->get('user_id');
        $store  = $this->storeModel->find($id);

        if (!$store || $store['user_id'] != $userId) {
            return redirect()->back()->with('error', 'Tidak diizinkan.');
        }

        // Hapus file logo
        if (!empty($store['logo'])) {
            $logoPath = $this->pathLogo . $store['logo'];
            if (file_exists($logoPath) && is_file($logoPath)) {
                unlink($logoPath);
            }
        }

        // Hapus file banner
        if (!empty($store['banner'])) {
            $bannerPath = $this->pathBanner . $store['banner'];
            if (file_exists($bannerPath) && is_file($bannerPath)) {
                unlink($bannerPath);
            }
        }

        // Hapus data toko
        $this->storeModel->delete($id);

        return redirect()->to('/store')->with('success', 'Toko berhasil dihapus.');
    }
}
