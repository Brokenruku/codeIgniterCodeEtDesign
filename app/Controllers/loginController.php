<?php

namespace App\Controllers;

use App\Models\UserModel;

class loginController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        return view('login');
    }

    public function authenticate()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method Not Allowed']);
        }

        // Get POST data
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validate inputs
        if (!$email || !$password) {
            return $this->response->setJSON(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
        }

        // Authenticate user
        $result = $this->userModel->authenticateUser($email, $password);

        if ($result['success']) {
            // Start session and store user data
            $session = session();
            $session->set('user_id', $result['user']['id']);
            $session->set('user_name', $result['user']['username']);
            $session->set('user_email', $result['user']['email']);
            $session->set('is_logged_in', true);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Connexion réussie!',
                'redirect' => '/home'
            ]);
        } else {
            return $this->response->setJSON($result);
        }
    }
}
