<?php

namespace App\Controller;

use App\Model\LogoManager;

class LogoController extends AbstractController
{
    public function show()
    {
        $logoManager = new LogoManager();
        $logos = $logoManager->selectAll();
        return $this->twig->render('Logos/logo-examples.html.twig', ['logo-examples' => $logos]);
    }
}
