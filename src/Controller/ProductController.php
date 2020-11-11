<?php

namespace App\Controller;

use App\Model\ProductManager;
use App\Model\CategoryManager;

class ProductController extends AbstractController
{
    /**
     * Display product informations specified by $id
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

    public function index()
    {
        $productManager = new ProductManager();
        $products = $productManager->selectAll();
        return $this->twig->render('Productadmin/index.html.twig', ['products' => $products]);
    }

    /**
     * Display product creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = array_map('trim', $_POST);
            $errors = $this->productValidation($product);
            if (empty($errors)) {
                $productManager = new ProductManager();
                $id = $productManager->insert($product);
                header('Location:/product/show/' . $id);
                return "votre produit a été rajouté";
            }
        }
        return $this->twig->render('Productadmin/add.html.twig', [
            'errors' => $errors ?? [],
            'product' => $product ?? [],
            'categories' => $categories,
            uniqid()
        ]);
    }

    /**
     * @param array $product
     * @return array
     * @SuppressWarnings(PHPMD)
     */

    private function productValidation(array $product): array
    {
        $errors = [];

        if (empty($product['name'])) {
            $errors[] = 'Le nom du produit doit être complété';
        }
        $length = 100;
        if (!empty($product['name']) && strlen($product['name']) > $length) {
            $errors[] = 'Le nom du produit doit contenir moins de ' . $length . ' caractères';
        }

        if (empty($product['gender'])) {
            $errors[] = 'Le genre doit être complété';
        }
        if (!empty($product['gender']) && strlen($product['name']) > $length) {
            $errors[] = 'Le genre doit contenir moins de' . $length . ' caractères';
        }

        if (empty($product['reference'])) {
            $errors[] = 'La référence du produit doit être complétée';
        }
        if (!empty($product['reference']) && strlen($product['name']) > $length) {
            $errors[] = 'La référence du produit doit contenir moins de' . $length . ' caractères';
        }
        if (empty($product['price'])) {
            $errors[] = 'Le prix doit être complété';
        }
        if (!empty($product['price']) && $product['price'] < 0) {
            $errors[] = 'Le prix ne peut être négatif';
        }
        if (!empty($product['image']) && !filter_var($product['image'], FILTER_VALIDATE_URL)) {
            $errors[] = 'L\'image doit être une URL valide';
        }
        return $errors ?? [];
    }
}
