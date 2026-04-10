<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $filters = [
            'q'      => $this->request->getGet('q'),
            'cat'    => $this->request->getGet('cat'),
            'min'    => $this->request->getGet('min'),
            'max'    => $this->request->getGet('max'),
            'sort'   => $this->request->getGet('sort'),
            'page'   => $this->request->getGet('page') ?? 1,
            'brand'  => $this->request->getGet('brand'),
            'stok'   => $this->request->getGet('stok'),
            'rating' => $this->request->getGet('rating'),
        ];

        return view('home/home', [
            'isLogin' => session()->get('logged_in') ?? false,
            'user'    => session()->get('user_name'),
            'filters' => $filters,
            'keyword' => $filters['q'], // Tambahkan keyword untuk digunakan di view
        ]);
    }
}