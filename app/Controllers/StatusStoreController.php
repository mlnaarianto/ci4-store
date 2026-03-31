<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StoreModel;
use CodeIgniter\HTTP\ResponseInterface;

class StatusStoreController extends BaseController
{
    protected $storeModel;

    public function __construct()
    {
        $this->storeModel = new StoreModel();
    }

    /**
     * Display list of stores for admin to manage status
     */
    public function index()
    {
        // Check if user is admin (you need to implement admin check)
        // if (!session()->get('is_admin')) {
        //     return redirect()->to('/')->with('error', 'Unauthorized access');
        // }

        $stores = $this->storeModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/stores/index', [
            'stores' => $stores
        ]);
    }

    /**
     * Show form to edit store status
     */
    public function edit($id)
    {
        // Check if user is admin
        // if (!session()->get('is_admin')) {
        //     return redirect()->to('/')->with('error', 'Unauthorized access');
        // }

        $store = $this->storeModel->find($id);

        if (!$store) {
            return redirect()->back()->with('error', 'Store not found');
        }

        return view('admin/stores/edit_status', [
            'store' => $store
        ]);
    }

    /**
     * Update store status (pending -> aktif / ditolak)
     */
    public function update($id)
    {
        // Check if user is admin
        // if (!session()->get('is_admin')) {
        //     return redirect()->to('/')->with('error', 'Unauthorized access');
        // }

        // Validation rules
        $rules = [
            'status' => 'required|in_list[pending,aktif,ditolak]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        // Find store
        $store = $this->storeModel->find($id);
        
        if (!$store) {
            return redirect()->back()->with('error', 'Store not found');
        }

        // Update status
        $newStatus = $this->request->getPost('status');
        
        // Optional: Add reason for rejection
        $data = [
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // If status is rejected, you might want to store rejection reason
        if ($newStatus === 'ditolak') {
            $rejectionReason = $this->request->getPost('rejection_reason');
            // You might need to add a 'rejection_reason' column to your stores table
            // $data['rejection_reason'] = $rejectionReason;
        }

        // Perform update
        if ($this->storeModel->update($id, $data)) {
            $message = "Store status has been updated to " . ucfirst($newStatus);
            
            // You can send notification to store owner here
            // $this->sendStatusNotification($store['user_id'], $newStatus, $rejectionReason ?? null);
            
            return redirect()->to('/admin/stores')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Failed to update store status');
        }
    }

    /**
     * Quick approve store (set to aktif)
     */
    public function approve($id)
    {
        // Check if user is admin
        // if (!session()->get('is_admin')) {
        //     return redirect()->to('/')->with('error', 'Unauthorized access');
        // }

        $store = $this->storeModel->find($id);
        
        if (!$store) {
            return redirect()->back()->with('error', 'Store not found');
        }

        // Update to active
        $this->storeModel->update($id, [
            'status' => 'aktif',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // You can send notification to store owner here
        // $this->sendStatusNotification($store['user_id'], 'aktif');

        return redirect()->to('/admin/stores')->with('success', 'Store has been approved and is now active');
    }

    /**
     * Quick reject store (set to ditolak)
     */
    public function reject($id)
    {
        // Check if user is admin
        // if (!session()->get('is_admin')) {
        //     return redirect()->to('/')->with('error', 'Unauthorized access');
        // }

        $store = $this->storeModel->find($id);
        
        if (!$store) {
            return redirect()->back()->with('error', 'Store not found');
        }

        // Validate rejection reason
        $rules = [
            'rejection_reason' => 'required|min_length[5]|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Rejection reason is required');
        }

        // Update to rejected
        $this->storeModel->update($id, [
            'status' => 'ditolak',
            // 'rejection_reason' => $this->request->getPost('rejection_reason'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // You can send notification to store owner here
        // $this->sendStatusNotification($store['user_id'], 'ditolak', $this->request->getPost('rejection_reason'));

        return redirect()->to('/admin/stores')->with('success', 'Store has been rejected');
    }

    /**
     * Optional: Send notification to store owner
     */
    private function sendStatusNotification($userId, $status, $reason = null)
    {
        // You can implement email notification or in-app notification here
        // For example:
        // $userModel = new \App\Models\UserModel();
        // $user = $userModel->find($userId);
        
        // $email = \Config\Services::email();
        // $email->setTo($user['email']);
        // $email->setSubject('Store Status Update');
        
        // $message = "Your store status has been updated to: " . ucfirst($status);
        // if ($reason) {
        //     $message .= "\nReason: " . $reason;
        // }
        
        // $email->setMessage($message);
        // $email->send();
    }
}