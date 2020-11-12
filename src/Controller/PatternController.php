<?php

namespace App\Controller;

use App\Model\PatternManager;

class PatternController extends AbstractController
{
    public function index()
    {
        $patternManager = new PatternManager();
        $patterns = $patternManager->selectAll();
        return $this->twig->render('Patternsadmin/index.html.twig', ['patterns' => $patterns]);
    }
    public function show()
    {
        $patternManager = new PatternManager();
        $patterns = $patternManager->selectAll();
        return $this->twig->render('Patterns/patterns.html.twig', ['patterns' => $patterns]);
    }
}
