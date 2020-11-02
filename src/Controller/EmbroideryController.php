<?php

namespace App\Controller;

use FilesystemIterator;

class EmbroideryController extends AbstractController
{
    public function examples()
    {
        $directoryPath = 'assets/images/embroidery-examples';
        $files = new FilesystemIterator($directoryPath, FilesystemIterator::SKIP_DOTS);
        return $this->twig->render('Embroidery/embroidery-examples.html.twig', ['files' => $files]);
    }
}
