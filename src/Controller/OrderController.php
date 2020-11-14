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
 * @SuppressWarnings(PHPMD)
 */
class OrderController extends AbstractController
{
    public const STATUSES = [
        'NEW' => "Nouvelle commande",
        'PROCESSING' => "En cours de traitement",
        'PROCESSED' => "Traitée",
        'SENT' => "Expédiée",
    ];
    public const PRODUCT_SIZES = ['S', 'M', 'L', 'XL',];
    public function sendQuote()
    {
        $order = [];
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $order = array_map('trim', $_POST);

            $errors = $this->quoteValidate($order);

            if (empty($errors)) {
                if (!empty($_FILES['userLogo']['name'])) {
                    $fileExtension = pathinfo($_FILES['userLogo']['name'], PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = 'uploads/';
                    move_uploaded_file($_FILES['userLogo']['tmp_name'], $uploadDir . $newFileName);
                } else {
                    $newFileName = '';
                }
                $order['userLogo'] = $newFileName;

                $orderManager = new OrderManager();
                $orderManager->saveQuote($order);
                header('Location:/order/thanks/');
            }
        }

        return $this->twig->render('Order/no-product_order_form.html.twig', [
            'order' => $order,
            'errors' => $errors]);
    }
    /**
     * @param array $order
     * @return array
     * @SuppressWarnings(PHPMD)
     */
    private function quoteValidate(array $order): array
    {
        $inputLength = 100;
        $shortInputLength = 20;
        $extensions = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
        $maxSize = 100000;

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
        if (
            !empty($_FILES['userLogo']['tmp_name']) &&
            !in_array(mime_content_type($_FILES['userLogo']['tmp_name']), $extensions)
        ) {
            $errors[] = 'Vous devez uploader un fichier de type png, gif, jpg ou jpeg';
        }
        if (!empty($_FILES['userLogo']['tmp_name']) && filesize($_FILES['userLogo']['tmp_name']) > $maxSize) {
            $errors[] = 'Le fichier doit faire moins de ' . $maxSize / 100000 . " Mo";
        }

        return $errors ?? [];
    }
    public function thanks()
    {
        return $this->twig->render('Order/thanks.html.twig');
    }
    public function editOrder(int $id)
    {
        $errors = [];
        $orderManager = new OrderManager();
        $order = $orderManager->selectOneById($id);

        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $order = array_map('trim', $_POST);
            $errors = $this->editValidate($order);

            if (empty($errors)) {
                $orderManager->updateOrder($order);
                header('Location:/home/index/');
            }
        }

        return $this->twig->render('OrderAdmin/edit.html.twig', ['order' => $order, 'errors' => $errors]);
    }
    private function editValidate(array $order): array
    {
        $errors = [];

        if (!in_array($order['status'], self::STATUSES)) {
            $errors[] = 'Les valeurs possibles sont : ' . implode(", ", self::STATUSES);
        }

        return $errors ?? [];
    }
    public function index()
    {
        $orderManager = new OrderManager();
        $orders = $orderManager->selectAllJoinProduct();
        return $this->twig->render('OrderAdmin/index.html.twig', ['orders' => $orders]);
    }
    public function show(int $id)
    {
        $orderManager = new OrderManager();
        $order = $orderManager->selectByIdJoinProduct($id);

        return $this->twig->render('OrderAdmin/show.html.twig', ['order' => $order]);
    }
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
                if (!empty($_FILES['userLogo']['name'])) {
                    $fileExtension = pathinfo($_FILES['userLogo']['name'], PATHINFO_EXTENSION);
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadDir = 'uploads/';
                    move_uploaded_file($_FILES['userLogo']['tmp_name'], $uploadDir . $newFileName);
                } else {
                    $newFileName = '';
                }
                $order['userLogo'] = $newFileName;

                $orderManager = new OrderManager();
                $orderManager->saveOrder($order, $product);
                header('Location:/order/thanks/');
            }
        }

        return $this->twig->render('Order/order_form.html.twig', ['product' => $product,
            'order' => $order,
            'sizes' => self::PRODUCT_SIZES,
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
        $extensions = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'];
        $maxSize = 100000;

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
        if (empty($order['size'])) {
            $errors[] = 'Le champ taille est obligatoire';
        }
        if (empty($order['quantity'])) {
            $errors[] = 'Le champ quantité est obligatoire';
        }
        if (
            !empty($_FILES['userLogo']['tmp_name']) &&
            !in_array(mime_content_type($_FILES['userLogo']['tmp_name']), $extensions)
        ) {
            $errors[] = 'Vous devez uploader un fichier de type png, gif, jpg ou jpeg';
        }
        if (!empty($_FILES['userLogo']['tmp_name']) && filesize($_FILES['userLogo']['tmp_name']) > $maxSize) {
            $errors[] = 'Le fichier doit faire moins de ' . $maxSize / 100000 . " Mo";
        }

        return $errors ?? [];
    }
}
