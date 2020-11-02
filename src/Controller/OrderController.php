<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\OrderManager;
use App\Model\ProductManager;

/**
 * Class OrderController
 *
 */
class OrderController extends AbstractController
{
    public function sendOrder(int $id)
    {
        $order = [];
        $errors = [];

        $productManager = new ProductManager();
        $product = $productManager->selectOneById($id);

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $order = array_map('trim', $_POST);
            $errors = $this->orderValidate($order);

            if (empty($errors)) {
                $orderManager = new OrderManager();
                $orderManager->saveOrder($order, $product);
                header('Location:/home/index/');
            }
        }

        return $this->twig->render('Order/order_form.html.twig', ['product' => $product,
            'order' => $order,
            'errors' => $errors]);
    }

    /**
     * @param array $order
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    private function orderValidate(array $order): array
    {
        $inputLength = 100;
        $shortInputLength = 20;
        $errors = [];
        if (empty($order['firstname'])) {
            $errors[] = 'Le champ prénom est obligatoire';
        }
        if (!empty($order['firstname']) && strlen($order['firstname']) > $inputLength) {
            $errors[] = 'Le champ prénom doit contenir moins de ' . $inputLength . ' caractères';
        }
        if (empty($order['lastname'])) {
            $errors[] = 'Le champ nom est obligatoire';
        }
        if (!empty($order['lastname']) && strlen($order['lastname']) > $inputLength) {
            $errors[] = 'Le champ nom doit faire contenir de ' . $inputLength . ' caractères';
        }
        if (empty($order['email'])) {
            $errors[] = 'Le champ email est obligatoire';
        }
        if (!empty($order['email']) && strlen($order['email']) > $inputLength) {
            $errors[] = 'Le champ email doit contenir moins de ' . $inputLength . ' caractères';
        }
        if (!empty($order['phone']) && strlen($order['phone']) > $shortInputLength) {
            $errors[] = 'Le champ téléphone doit contenir moins de ' . $shortInputLength . ' caractères';
        }
        if (!empty($order['companyName']) && strlen($order['companyName']) > $inputLength) {
            $errors[] = 'Le champ raison sociale doit contenir moins de ' . $inputLength . ' caractères';
        }
        if (empty($order['address'])) {
            $errors[] = 'Le champ adresse est obligatoire';
        }
        if (!empty($order['address']) && strlen($order['address']) > $inputLength) {
            $errors[] = 'Le champ addresse doit contenir moins de ' . $inputLength . ' caractères';
        }
        if (empty($order['postcode'])) {
            $errors[] = 'Le champ code postal est obligatoire';
        }
        if (!empty($order['postcode']) && strlen($order['postcode']) > $shortInputLength) {
            $errors[] = 'Le champ code postal doit contenir moins de ' . $shortInputLength . ' caractères';
        }
        if (empty($order['city'])) {
            $errors[] = 'Le champ ville est obligatoire';
        }
        if (!empty($order['city']) && strlen($order['city']) > $inputLength) {
            $errors[] = 'Le champ ville doit contenir moins de ' . $inputLength . ' caractères';
        }

        return $errors ?? [];
    }
}
