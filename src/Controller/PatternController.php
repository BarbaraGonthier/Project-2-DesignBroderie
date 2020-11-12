<?php

namespace App\Controller;

use App\Model\PatternManager;

class PatternController extends AbstractController
{
    public function index()
    {
        $patternManager = new PatternManager();
        $patterns = $patternManager->selectAll();
        return $this->twig->render('Patternsadmin/index.html.twig', ['patterns' => $patterns]);
    }

    public function show()
    {
        $patternManager = new PatternManager();
        $patterns = $patternManager->selectAll();
        return $this->twig->render('Patterns/patterns.html.twig', ['patterns' => $patterns]);
    }

    public function addPattern()
    {
        $pattern = [];
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $pattern = array_map('trim', $_POST);
            $errors = $this->patternsValidate($pattern);

            if (empty($errors)) {
                if (!empty($_FILES['photo']['name'])) {
                    $fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = 'uploads/patterns/';
                    move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFileName);
                    $pattern['photo'] = $newFileName;
                }


                $patternManager = new PatternManager();
                $patternManager->insert($pattern);
                header('Location:/Home/index/');
            }
        }

        return $this->twig->render('Patternsadmin/add.html.twig', ['pattern' => $pattern, 'errors' => $errors]);
    }

    /**
     * @param array $pattern
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    private function patternsValidate(array $pattern): array
    {
        $extensions = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
        $maxSize = 5000000;

        $size = filesize($_FILES['photo']['tmp_name']);

        $errors = [];

        if (empty($pattern['name'])) {
            $errors[] = 'Le champ prénom est obligatoire';
        }
        if (
            !empty($_FILES['photo']['tmp_name']) && !in_array(
                mime_content_type($_FILES['photo']['tmp_name']),
                $extensions
            )
        ) {
            $errors[] = 'Vous devez uploader un fichier de type png, gif, jpg ou jpeg';
        }
        if ($size > $maxSize) {
            $errors[] = 'Le fichier doit faire moins de ' . $maxSize / 5000000 . " Mo";
        }
        if (empty($_FILES['photo']['name'])) {
            $errors[] = "Vous devez insérer une image.";
        }

        return $errors ?? [];
    }
}
