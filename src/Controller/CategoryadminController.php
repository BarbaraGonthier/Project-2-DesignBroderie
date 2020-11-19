<?php

namespace App\Controller;

use App\Model\CategoryManager;

class CategoryadminController extends AbstractController
{

    public function delete()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'];
            $categoryManager = new CategoryManager();
            $categories = $categoryManager->selectOneById($id);

            $categoryManager->delete($id);
            return $this->twig->render('Categoryadmin/delete.html.twig', ['$categories' => $categories]);
        } else {
            echo "merci de vous connecter à votre espace admin et de choisir une catégories à supprimer";
        }
    }

    public function index()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        return $this->twig->render('/Categoryadmin/index.html.twig', ['categories' => $categories]);
    }
}
