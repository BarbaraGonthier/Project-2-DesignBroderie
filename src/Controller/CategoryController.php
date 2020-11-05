<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Model\ProductManager;

class CategoryController extends AbstractController
{
    public function index()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        return $this->twig->render('/Product/category.html.twig', ['categories' => $categories]);
    }
}
