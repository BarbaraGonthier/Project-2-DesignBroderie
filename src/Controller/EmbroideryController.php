<?php

namespace App\Controller;

class EmbroideryController extends AbstractController
{
    public function examples()
    {
        return $this->twig->render('Embroidery/embroidery-examples.html.twig');
    }
}
