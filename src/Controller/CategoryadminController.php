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
    public function addCategories()
    {
        $categories = [];
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $categories = array_map('trim', $_POST);
            $errors = $this->categoriesValidate($categories);

            if (empty($errors)) {
                if (!empty($_FILES['image']['name'])) {
                    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = 'uploads/category/';
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFileName);
                    $categories['image'] = $newFileName;
                }


                $categoryManager = new CategoryManager();
                $categoryManager->insert($categories);
                header('Location:/categoryadmin/index');
            }
        }

        return $this->twig->render('Categoryadmin/add.html.twig', ['categories' => $categories, 'errors' => $errors]);
    }

    /**
     * @param array $categories
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    private function categoriesValidate(array $categories): array
    {
        $extensions = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
        $maxSize = 2000000;

        $size = filesize($_FILES['image']['tmp_name']);

        $errors = [];

        if (empty($categories['name'])) {
            $errors[] = 'Le champ Nom du modèle est obligatoire';
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
            $errors[] = 'Le fichier doit faire moins de ' . $maxSize / 1000000 . " Mo";
        }
        if (empty($_FILES['image']['name'])) {
            $errors[] = "Vous devez insérer une image.";
        }

        return $errors ?? [];
    }

    public function index()
    {
        $disabledCategories = [];
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        $usedCategories = $categoryManager->selectAllUsed();

        foreach ($usedCategories as $key) {
            foreach ($key as $value) {
                $disabledCategories[] = $value;
            }
        }
        return $this->twig->render('/Categoryadmin/index.html.twig', ['categories' => $categories,
            'disabledCategories' => $disabledCategories,]);
    }
}
