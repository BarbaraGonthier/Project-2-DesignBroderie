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
 /**
 * Handle item deletion
 *
/* * @param int $id
 */
    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'];
            $productManager = new ProductManager();
            $product = $productManager->selectOneById($id);

            $productManager->delete($id);
            return $this->twig->render('Productadmin/confirmdelete.html.twig', ['name' => $product["name"]]);
        } else {
            echo "merci de vous connecter à votre espace admin et de choisir un produit à supprimer";
        }
    }
}
