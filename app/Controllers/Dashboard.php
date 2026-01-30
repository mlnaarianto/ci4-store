<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('tampilan/dashboard', [
            'title' => 'Dashboard'
        ]);
    }
}
