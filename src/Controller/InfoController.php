<?php

namespace App\Controller;

use App\Model\InfoManager;

class InfoController extends AbstractController
{
    public function infoSend()
    {
        $info = [];
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $info = array_map('trim', $_POST);
            $errors = $this->infoValidate($info);

            if (empty($errors)) {
                $infoManager = new InfoManager();
                $infoManager->infoSave($info);
                header('Location:/home/index/');
            }
        }

        return $this->twig->render('Info/info_form.html.twig', [
            'info' => $info,
            'errors' => $errors]);
    }

    public function infoValidate(array $info): array
    {
        $inputLength = 100;
        $errors = [];
        if (empty($info['firstname'])) {
            $errors[] = 'Le champ prénom est obligatoire';
        }
        if (strlen($info['firstname']) > $inputLength) {
            $errors[] = 'Le champ prénom doit contenir moins de ' . $inputLength . ' caractères';
        }
        if (empty($info['lastname'])) {
            $errors[] = 'Le champ nom est obligatoire';
        }
        if (strlen($info['lastname']) > $inputLength) {
            $errors[] = 'Le champ nom doit contenir moins de ' . $inputLength . ' caractères';
        }
        if (empty($info['email'])) {
            $errors[] = 'Le champ email est obligatoire';
        }
        if (strlen($info['email']) > $inputLength) {
            $errors[] = 'Le champ email doit contenir moins de ' . $inputLength . ' caractères';
        }
        return $errors ?? [];
    }
}
