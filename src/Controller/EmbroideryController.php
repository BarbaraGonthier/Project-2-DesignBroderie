<?php

namespace App\Controller;

use App\Model\EmbroideryManager;

class EmbroideryController extends AbstractController
{
    public function examples()
    {
        $embroideryManager = new EmbroideryManager();
        $embroideries = $embroideryManager->selectAll();
        return $this->twig->render('Embroidery/embroidery-examples.html.twig', ['embroideries' => $embroideries]);
    }
}
