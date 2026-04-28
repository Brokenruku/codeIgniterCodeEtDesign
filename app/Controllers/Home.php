<?php

namespace App\Controllers;

use App\Models\PlatModel;

class Home extends BaseController
{
    public function index()
    {
        try {
            $platModel = new PlatModel();
            
            $plats = $platModel->getAllWithCategorie();
            
            $foods = [];
            foreach ($plats as $plat) {
                $foods[] = [
                    'id' => $plat['id'],
                    'name' => $plat['name'],
                    'emoji' => $plat['emoji'] ?? '🍽️',
                    'img' => $plat['image_url'] ?? null,
                    'cat' => $plat['categorie_nom'],
                    'time' => ($plat['temps'] ?? 0) . ' min',
                    'cal' => ($plat['calorie'] ?? 0) . ' kcal',
                    'rating' => ($plat['note'] ?? 0) . '.0',
                    'desc' => $plat['description'] ?? '',
                ];
            }
            
            return view('home', ['foods' => $foods]);
            
        } catch (\Exception $e) {
            echo "Erreur: " . $e->getMessage();
            echo "<br>Fichier: " . $e->getFile();
            echo "<br>Ligne: " . $e->getLine();
            die();
        }
    }
}