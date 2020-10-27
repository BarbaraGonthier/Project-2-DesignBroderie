<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\QuoteManager;

/**
 * Class QuoteController
 *
 */
class QuoteController extends AbstractController
{
    public function quoteForm()
    {
        return $this->twig->render('Quote/quote_form.html.twig');
    }
}