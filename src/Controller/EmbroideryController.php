<?php

namespace App\Controller;

use App\Model\CategoryManager;
use App\Model\EmbroideryManager;

class EmbroideryController extends AbstractController
{
    public function examples()
    {
        $embroideryManager = new EmbroideryManager();
        $embroideries = $embroideryManager->selectAll();
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        return $this->twig->render(
            'Embroidery/embroidery-examples.html.twig',
            ['embroideries' => $embroideries, 'categories' => $categories]
        );
    }
}
