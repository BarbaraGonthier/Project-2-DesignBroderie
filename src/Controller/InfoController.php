<?php

namespace App\Controller;

use App\Model\InfoManager;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class InfoController extends AbstractController
{
    public function infoSend()
    {
        $contact = [];
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $contact = array_map('trim', $_POST);
            $errors = $this->infoValidate($contact);

            if (empty($errors)) {
                $transport = Transport::fromDsn(MAILER_DSN);
                $mailer = new Mailer($transport);
                $email = (new Email())
                    ->from($contact['email'])
                    ->to(MAIL_TO)
                    ->subject('Message de Design Broderie')
                    ->html('<h1> Message de ' . $contact['firstname'] .
                        ' ' . $contact['lastname'] . '</h1><p>' . $contact['message'] . '</p>');

                $mailer->send($email);
                header('Location:/home/index/');
            }
        }

        return $this->twig->render('Info/info_form.html.twig', [
            'info' => $contact,
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
