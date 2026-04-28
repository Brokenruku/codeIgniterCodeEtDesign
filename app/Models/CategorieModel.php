<?php

namespace App\Models;

use CodeIgniter\Model;

class CategorieModel extends Model
{
    protected $table = 'CATEGORIE';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom'];
    protected $useTimestamps = false;
    
    public function getOrCreate($nom)
    {
        $categorie = $this->where('nom', $nom)->first();
        if ($categorie) {
            return $categorie['id'];
        }
        
        $this->insert(['nom' => $nom]);
        return $this->insertID();
    }
    
    public function getAllCategories()
    {
        return $this->findAll();
    }
}