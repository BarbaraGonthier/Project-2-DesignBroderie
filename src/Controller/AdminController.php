<?php

namespace App\Controller;

class AdminController extends AbstractController
{

    private const LOGIN = 'Hobbit';

    public function home()
    {
        return $this->twig->render('Homeadmin/admin.html.twig');
    }
}
