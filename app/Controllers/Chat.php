<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Chat extends BaseController
{
    public function index()
    {
        // Test tanpa layout dulu
        return view('chat/chats', ['title' => 'Halaman Chat']);
    }
}
