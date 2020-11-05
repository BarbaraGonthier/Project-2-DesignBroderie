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
    public function list(int $categoryId)
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        $productManager = new ProductManager();
        $products = $productManager->selectAllByCategoryId($categoryId);
        return $this->twig->render(
            'Product/productByCategory.html.twig',
            ['products' => $products, 'categories' => $categories]
    }
}
