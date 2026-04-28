<?php

namespace App\Models;

use CodeIgniter\Model;

class InteractionModel extends Model
{
    protected $table = 'user_interactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'plat_id', 'action'];
    protected $useTimestamps = false;
    
    public function addInteraction($userId, $platId, $action)
    {
        // Vérifier si l'interaction existe déjà
        $existing = $this->where('user_id', $userId)
                         ->where('plat_id', $platId)
                         ->where('action', $action)
                         ->first();
        
        if (!$existing) {
            return $this->insert([
                'user_id' => $userId,
                'plat_id' => $platId,
                'action' => $action
            ]);
        }
        
        return false;
    }
    
    public function getUserStats($userId)
    {
        $db = \Config\Database::connect();
        
        // Nombre de vus
        $seen = $this->where('user_id', $userId)
                     ->where('action', 'seen')
                     ->countAllResults();
        
        // Nombre de likes
        $liked = $this->where('user_id', $userId)
                      ->where('action', 'like')
                      ->countAllResults();
        
        // Nombre de super likes
        $super = $this->where('user_id', $userId)
                      ->where('action', 'super')
                      ->countAllResults();
        
        // Plats likés avec détails (sans jointure pour éviter les erreurs)
        $likedInteractions = $this->where('user_id', $userId)
                                  ->where('action', 'like')
                                  ->orderBy('created_at', 'DESC')
                                  ->findAll();
        
        $likedPlats = [];
        $platModel = new \App\Models\PlatModel();
        
        foreach ($likedInteractions as $interaction) {
            $plat = $platModel->find($interaction['plat_id']);
            if ($plat) {
                // Récupérer la catégorie
                $categorieModel = new \App\Models\CategorieModel();
                $categorie = $categorieModel->find($plat['categorie_id']);
                
                $likedPlats[] = [
                    'plat_id' => $plat['id'],
                    'name' => $plat['name'],
                    'emoji' => $plat['emoji'],
                    'image_url' => $plat['image_url'],
                    'temps' => $plat['temps'],
                    'calorie' => $plat['calorie'],
                    'categorie_nom' => $categorie ? $categorie['nom'] : 'Non catégorisé'
                ];
            }
        }
        
        // Catégories likées
        $categories = [];
        foreach ($likedPlats as $plat) {
            $catName = $plat['categorie_nom'];
            if (isset($categories[$catName])) {
                $categories[$catName]++;
            } else {
                $categories[$catName] = 1;
            }
        }
        
        arsort($categories);
        
        $categoriesArray = [];
        foreach ($categories as $nom => $count) {
            $categoriesArray[] = ['nom' => $nom, 'count' => $count];
        }
        
        $superInteractions = $this->where('user_id', $userId)
                                  ->where('action', 'super')
                                  ->findAll();
        $superIds = array_column($superInteractions, 'plat_id');
        
        return [
            'seen' => $seen,
            'liked' => $liked,
            'super' => $super,
            'liked_plats' => $likedPlats,
            'categories' => $categoriesArray,
            'super_ids' => $superIds
        ];
    }
    
    public function getSeenIds($userId)
    {
        $interactions = $this->where('user_id', $userId)
                             ->where('action', 'seen')
                             ->findAll();
        return array_column($interactions, 'plat_id');
    }
}