<?php

namespace App\Controllers;

use App\Models\UserModel;

class inscriptionController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        return view('inscription');
    }

    public function register()
    {
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method Not Allowed']);
        }

        // Get POST data
        $username = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirmPassword');

        // Validate inputs
        if (!$username || !$email || !$password || !$confirmPassword) {
            return $this->response->setJSON(['success' => false, 'message' => 'Veuillez remplir tous les champs.']);
        }

        if (strlen($password) < 8) {
            return $this->response->setJSON(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères.']);
        }

        if ($password !== $confirmPassword) {
            return $this->response->setJSON(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email invalide.']);
        }

        // Register user
        $result = $this->userModel->registerUser($email, $username, $password);

        if ($result['success']) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Inscription réussie!',
                'redirect' => '/login'
            ]);
        } else {
            return $this->response->setJSON($result);
        }
    }
}
