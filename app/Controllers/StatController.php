<?php

namespace App\Controllers;

use App\Models\InteractionModel;
use App\Models\PlatModel;
use App\Models\CategorieModel;

class StatController extends BaseController
{
    public function index()
    {
        $userId = 1;
        
        $interactionModel = new InteractionModel();
        
        $stats = $interactionModel->getUserStats($userId);
        
        $likedFoods = [];
        foreach ($stats['liked_plats'] as $liked) {
            $likedFoods[] = [
                'id' => $liked['plat_id'],
                'name' => $liked['name'],
                'emoji' => $liked['emoji'],
                'img' => $liked['image_url'],
                'cat' => $liked['categorie_nom'],
                'time' => $liked['temps'] . ' min',
                'cal' => $liked['calorie'] . ' kcal'
            ];
        }
        
        $catColors = [
            '#FF6B6B','#FF8E53','#FFC371','#4ECDC4','#45B7D1',
            '#96CEB4','#DDA0DD','#FF69B4','#20B2AA','#9370DB','#F08080','#3CB371'
        ];
        
        return view('stat', [
            'totalSeen' => $stats['seen'],
            'totalLiked' => $stats['liked'],
            'totalSuper' => $stats['super'],
            'likedFoods' => $likedFoods,
            'categories' => $stats['categories'],
            'superIds' => $stats['super_ids'],
            'catColors' => $catColors
        ]);
    }
    
    public function saveInteraction()
    {
        $userId = 1;
        
        $platId = $this->request->getPost('plat_id');
        $action = $this->request->getPost('action'); 
        
        if (!$platId || !$action) {
            return $this->response->setJSON(['success' => false, 'error' => 'Données manquantes']);
        }
        
        $interactionModel = new InteractionModel();
        
        try {
            $interactionModel->addInteraction($userId, $platId, 'seen');
            
            if ($action === 'like' || $action === 'super') {
                $interactionModel->addInteraction($userId, $platId, $action);
            }
            
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}