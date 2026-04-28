<?php

namespace App\Models;

use CodeIgniter\Model;

class PlatModel extends Model
{
    protected $table = 'plats';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'image_url', 'note', 'emoji', 'temps', 'categorie_id', 'calorie'];
    protected $useTimestamps = false;
    
    public function getAllWithCategorie()
    {
        return $this->select('plats.*, CATEGORIE.nom as categorie_nom')
                    ->join('CATEGORIE', 'CATEGORIE.id = plats.categorie_id')
                    ->findAll();
    }
    
    public function getRandomPlats($limit = 20)
    {
        return $this->select('plats.*, CATEGORIE.nom as categorie_nom')
                    ->join('CATEGORIE', 'CATEGORIE.id = plats.categorie_id')
                    ->orderBy('RAND()')
                    ->findAll($limit);
    }
}