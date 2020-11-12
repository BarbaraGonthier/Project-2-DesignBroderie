<?php

namespace App\Controller;

use App\Model\ProductManager;

class ProductController extends AbstractController
{
    /**
     * Display item informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $productManager = new ProductManager();
        $product = $productManager->selectOneByIdJoinCategory($id);

        return $this->twig->render('Productadmin/show.html.twig', ['product' => $product]);
    }
    public function index()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAll();
        return $this->twig->render('Productadmin/index.html.twig', ['products' => $products]);
    }

    public function list(int $categoryId)
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAllByCategoryId($categoryId);
        return $this->twig->render(
            'Product/productByCategory.html.twig',
            ['products' => $products]
        );
    }
}
