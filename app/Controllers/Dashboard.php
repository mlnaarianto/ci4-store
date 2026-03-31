<?php

namespace App\Controllers;

use App\Models\User;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new User();

        $totalPembeli = $userModel
            ->where('role', 'pembeli')
            ->countAllResults();

        $totalPenjual = $userModel
            ->where('role', 'penjual')
            ->countAllResults();

        // Hitung semua user
        $totalUsers = $userModel->countAll();

        return view('admin/dashboard', [
            'user'          => session()->get('user_name'),
            'totalPembeli'  => $totalPembeli,
            'totalPenjual'  => $totalPenjual,
            'totalUsers'    => $totalUsers,
        ]);
    }
}