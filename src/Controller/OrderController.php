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
        $productManager = new ProductManager();
        $product = $productManager->selectOneById($id);

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $orderManager = new OrderManager();
            $order = array_map('trim', $_POST);
            $order = [
                'productId' => $id,
            ];
            $errors = $this->orderValidate($order);

            if (empty($errors)) {
                $orderManager->saveOrder($order);
                header('Location: /');
            }
        }

        return $this->twig->render('Order/order_form.html.twig', ['product' => $product]);
    }

    /**
     * @param array $order
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    private function orderValidate(array $order): array
    {
        $errors = [];
        if (empty($order['firstname'])) {
            $errors[] = 'Le champ prénom est obligatoire';
        }
        if (!empty($order['firstname']) && strlen($order['firstname']) > 100) {
            $errors[] = 'Le champ prénom doit contenir moins de 100 caractères';
        }
        if (empty($order['lastname'])) {
            $errors[] = 'Le champ nom est obligatoire';
        }
        if (!empty($order['lastname']) && strlen($order['lastname']) > 100) {
            $errors[] = 'Le champ nom doit faire contenir de 100 caractères';
        }
        if (empty($order['email'])) {
            $errors[] = 'Le champ email est obligatoire';
        }
        if (!empty($order['email']) && strlen($order['email']) > 100) {
            $errors[] = 'Le champ email doit contenir moins de 100 caractères';
        }
        if (!empty($order['phone']) && strlen($order['phone']) > 20) {
            $errors[] = 'Le champ téléphone doit contenir moins de 20 caractères';
        }
        if (!empty($order['companyName']) && strlen($order['companyName']) > 100) {
            $errors[] = 'Le champ raison sociale doit contenir moins de 100 caractères';
        }
        if (empty($order['address'])) {
            $errors[] = 'Le champ adresse est obligatoire';
        }
        if (!empty($order['address']) && strlen($order['address']) > 100) {
            $errors[] = 'Le champ addresse doit contenir moins de 100 caractères';
        }
        if (empty($order['postcode'])) {
            $errors[] = 'Le champ code postal est obligatoire';
        }
        if (!empty($order['postcode']) && strlen($order['postcode']) > 20) {
            $errors[] = 'Le champ code postal doit contenir moins de 20 caractères';
        }
        if (empty($order['ville'])) {
            $errors[] = 'Le champ ville est obligatoire';
        }
        if (!empty($order['ville']) && strlen($order['ville']) > 100) {
            $errors[] = 'Le champ ville doit contenir moins de 100 caractères';
        }

        return $errors ?? [];
    }
}
