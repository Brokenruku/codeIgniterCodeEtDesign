<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'password', 'username'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;

    /**
     * Register a new user
     */
    public function registerUser($email, $name, $password)
    {
        // Check if user already exists
        $existing = $this->where('email', $email)->first();
        if ($existing) {
            return ['success' => false, 'message' => 'email déjà utilisé.'];
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user
        $data = [
            'email'    => $email,
            'username'     => $name,
            'password' => $hashedPassword
        ];

        if ($this->insert($data)) {
            return ['success' => true, 'message' => 'Inscription réussie!'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de l\'inscription.'];
        }
    }

    /**
     * Authenticate a user
     */
    public function authenticateUser($email, $password)
    {
        $user = $this->where('email', $email)->first();

        if (!$user) {
            return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Remove password from returned user data
            unset($user['password']);
            return ['success' => true, 'user' => $user, 'message' => 'Connexion réussie!'];
        } else {
            return ['success' => false, 'message' => 'Email ou mot de passe incorrect.'];
        }
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }
}
