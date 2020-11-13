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
                if (!empty($_FILES['image']['name'])) {
                    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = 'uploads/product/';
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFileName);
                    $product['image'] = $newFileName;
                }
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

        ]);
    }
    /**
     * @param array $product
     * @return array
     * @SuppressWarnings(PHPMD)
     */

    private function productValidation(array $product): array
    {
        $extensions = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
        $maxSize = 5000000;

        $size = filesize($_FILES['image']['tmp_name']);
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
        if (
            !empty($_FILES['image']['tmp_name']) && !in_array(
                mime_content_type($_FILES['image']['tmp_name']),
                $extensions
            )
        ) {
            $errors[] = 'Vous devez uploader un fichier de type png, gif, jpg ou jpeg';
        }
        if ($size > $maxSize) {
            $errors[] = 'Le fichier doit faire moins de ' . $maxSize / 5000000 . " Mo";
        }
        if (empty($_FILES['image']['name'])) {
            $errors[] = "Vous devez insérer une image.";
        }
        if (empty($product['price'])) {
            $errors[] = 'Le prix doit être complété';
        }
        if (!empty($product['price']) && $product['price'] < 0) {
            $errors[] = 'Le prix ne peut être négatif';
        }

        return $errors ?? [];
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
