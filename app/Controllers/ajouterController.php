<?php

namespace App\Controllers;

use App\Models\CategorieModel;
use App\Models\PlatModel;

class AjouterController extends BaseController
{
    public function index()
    {
        $categorieModel = new CategorieModel();
        $categories = $categorieModel->getAllCategories();
        
        return view('ajouter', ['categories' => $categories]);
    }
    
    public function save()
    {
        helper('filesystem');
        
        $rules = [
            'name' => 'required|min_length[2]|max_length[255]',
            'categorie' => 'required',
            'temps' => 'required|numeric|greater_than[0]',
            'calorie' => 'required|numeric|greater_than[0]',
            'note' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[5]',
            'description' => 'permit_empty|max_length[500]',
            'emoji' => 'permit_empty|max_length[10]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors()
            ]);
        }
        
        $categorieModel = new CategorieModel();
        $categorie_id = $categorieModel->getOrCreate($this->request->getPost('categorie'));
        
        $image_url = null;
        $imageFile = $this->request->getFile('image');
        
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
            if (!in_array($imageFile->getMimeType(), $allowedTypes)) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['image' => 'Format non accepté. Utilisez JPG, PNG ou WEBP.']
                ]);
            }
            
            if ($imageFile->getSize() > 5 * 1024 * 1024) {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => ['image' => 'L\'image dépasse 5 Mo.']
                ]);
            }
            
            $newName = $imageFile->getRandomName();
            
            $imageFile->move('uploads/images', $newName);
            $image_url = 'uploads/images/' . $newName;
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'image_url' => $image_url,
            'note' => $this->request->getPost('note'),
            'emoji' => $this->request->getPost('emoji') ?: '🍽️',
            'temps' => $this->request->getPost('temps'),
            'categorie_id' => $categorie_id,
            'calorie' => $this->request->getPost('calorie')
        ];
        
        $platModel = new PlatModel();
        
        try {
            $platModel->insertPlat($data);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Plat ajouté avec succès !'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => ['database' => 'Erreur lors de l\'insertion: ' . $e->getMessage()]
            ]);
        }
    }
}