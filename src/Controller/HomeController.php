<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\CategoryManager;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        $categoryManager = new CategoryManager();
        $categoryLimit = $categoryManager->selectAllLimit();
        return $this->twig->render('Home/index.html.twig', ['categories' => $categories,
            'categoryLimit' => $categoryLimit]);
    }
    public function legalTerms()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->selectAll();
        return $this->twig->render('Home/legalterms.html.twig', ['categories' => $categories]);
    }
}
