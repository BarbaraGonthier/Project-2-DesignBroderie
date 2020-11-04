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
        $product = $productManager->selectOneById($id);

        return $this->twig->render('Productadmin/show.html.twig', ['product' => $product]);
    }
}
