<?php

use App\Models\User;

if (!function_exists('current_user')) {
    function current_user()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return null;
        }

        $userModel = new User();
        return $userModel->find($userId);
    }
}

if (!function_exists('is_profile_complete')) {
    function is_profile_complete($user = null): bool
    {
        if (!$user) {
            $user = current_user();
        }

        if (!$user) {
            return false;
        }

        $requiredFields = ['name', 'nomor_hp', 'alamat'];

        foreach ($requiredFields as $field) {
            if (!isset($user[$field]) || empty(trim($user[$field]))) {
                return false;
            }
        }

        return true;
    }
}