<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectBasedOnRole();
        }
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            $pwdVerify = password_verify($password, $user['password_hash']);
            
            // Fallback for manually added users (plain text password)
            if (!$pwdVerify && $password === $user['password_hash']) {
                // Determine new hash
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                // Update database
                $model->update($user['id'], ['password_hash' => $newHash]);
                // Update local var for session creation check (conceptually valid now)
                $pwdVerify = true;
                $session->setFlashdata('success', 'Security Upgraded: Your password has been encrypted.');
            }

            if ($pwdVerify) {
                $ses_data = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'full_name' => $user['full_name'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                $this->logActivity('Login', ucfirst($user['role']) . ' logged in (' . $user['username'] . ')');

                return $this->redirectBasedOnRole();
            } else {
                $session->setFlashdata('error', 'Invalid Password.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Username not Found.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $userId = session()->get('id');
        $role = session()->get('role');
        $username = session()->get('username');
        if ($userId) {
            $this->logActivity('Logout', ($role ? ucfirst($role) . ' ' : '') . ($username ?: 'User') . ' logged out');
        }
        session()->destroy();

        return redirect()->to('/login');
    }

    private function redirectBasedOnRole()
    {
        $role = session()->get('role');
        switch ($role) {
            case 'admin':
                return redirect()->to('/admin/dashboard');
            case 'staff':
                return redirect()->to('/staff/dashboard');
            default:
                return redirect()->to('/guest');
        }
    }

    public function seedUsers()
    {
        $model = new UserModel();
        
        // Define users to seed
        $users = [
            [
                'username' => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'full_name' => 'System Administrator'
            ],
            [
                'username' => 'staff',
                'password_hash' => password_hash('staff123', PASSWORD_DEFAULT),
                'role' => 'staff',
                'full_name' => 'Staff User'
            ],
        ];

        $output = "<h3>Seeding Users...</h3><ul>";

        foreach ($users as $user) {
            // Check if user exists
            $existing = $model->where('username', $user['username'])->first();
            
            if ($existing) {
                // Update existing
                $model->update($existing['id'], $user);
                $output .= "<li>Updated user: <strong>{$user['username']}</strong> (Password: {$user['username']}123)</li>";
            } else {
                // Insert new
                $model->insert($user);
                $output .= "<li>Created user: <strong>{$user['username']}</strong> (Password: {$user['username']}123)</li>";
            }
        }
              
        $output .= "</ul><p><a href='".base_url('login')."'>Go to Login</a></p>";
        return $output;
    }
}
