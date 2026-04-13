<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Chat extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');

        // ================= ADMIN =================
        if ($role === 'admin') {
            return view('admin/chat', [
                'title'     => 'Chat User',
                'user_id'   => session()->get('user_id'),
                'username'  => session()->get('user_name'),
                'role'      => $role
            ]);
        }

        // ================= USER =================
        return view('chat/chats', [
            'title'     => 'Chat Admin',
            'user_id'   => session()->get('user_id'),
            'username'  => session()->get('user_name'),
            'role'      => $role,
            'avatar'    => session()->get('user_avatar')
        ]);
    }

    // public function room($roomId)
    // {
    //     if (!session()->get('logged_in')) {
    //         return redirect()->to('/login');
    //     }

    //     $role = session()->get('role');

    //     // hanya admin boleh akses ini
    //     if ($role !== 'admin') {
    //         return redirect()->to('/chat');
    //     }

    //     return view('admin/chat_room', [
    //         'title'     => 'Chat Room',
    //         'room_id'   => $roomId,
    //         'user_id'   => session()->get('user_id'),
    //         'username'  => session()->get('user_name'),
    //         'role'      => $role
    //     ]);
    // }
}
