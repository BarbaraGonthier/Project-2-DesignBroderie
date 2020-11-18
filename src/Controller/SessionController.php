<?php

namespace App\Controller;

use App\Model\CategoryManager;

class SessionController extends AbstractController
{
    public function login()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        return $this->twig->render('Admin/login.html.twig', ['categories' => $categories]);
    }
}
