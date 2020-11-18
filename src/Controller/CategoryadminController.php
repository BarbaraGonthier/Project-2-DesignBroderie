<?php

namespace App\Controller;

use App\Model\CategoryManager;

class CategoryadminController extends AbstractController
{
    public function index()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        return $this->twig->render('/Categoryadmin/index.html.twig', ['categories' => $categories]);
    }
}
