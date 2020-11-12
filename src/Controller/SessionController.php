<?php

namespace App\Controller;

class SessionController extends AbstractController
{
    public function login()
    {
        return $this->twig->render('Admin/login.html.twig');
    }
}
