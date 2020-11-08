<?php

/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class OrderManager extends AbstractManager
{
    public const TABLE = "`order`";

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }

    public function selectByIdJoinProduct(int $id): array
    {
        $statement = $this->pdo->prepare("SELECT CONCAT(o.firstname,' ', o.lastname) AS fullname, 
        CONCAT(o.email, ' / ',o.phone) AS email_tel, 
        CONCAT(o.company_name, ' ', o.address, ' ', o.postcode, ' ', o.city) AS full_address, 
        o.size, o.quantity, o.message, o.product_id, o.user_logo, o.status, p.name FROM " . self::TABLE . " o 
        JOIN product p ON p.id=o.product_id WHERE o.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
    public function saveOrder(array $order, $product, $userLogo)
    {
        $query = "INSERT INTO " . self::TABLE .
            " (`firstname`, `lastname`, `email`, `phone`, `company_name`, 
            `address`, `city`, `postcode`, `size`, `quantity`, `message`, `product_id`, `user_logo`) 
            VALUES 
            (:firstname, :lastname, :email, :phone, :company_name, 
            :address, :city, :postcode, :size, :quantity, :message, :product_id, :user_logo)";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':firstname', $order['firstname'], \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $order['lastname'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $order['email'], \PDO::PARAM_STR);
        $statement->bindValue(':phone', $order['phone'], \PDO::PARAM_STR);
        $statement->bindValue(':company_name', $order['companyName'], \PDO::PARAM_STR);
        $statement->bindValue(':address', $order['address'], \PDO::PARAM_STR);
        $statement->bindValue(':city', $order['city'], \PDO::PARAM_STR);
        $statement->bindValue(':postcode', $order['postcode'], \PDO::PARAM_STR);
        $statement->bindValue(':size', $order['size'], \PDO::PARAM_STR);
        $statement->bindValue(':quantity', $order['quantity'], \PDO::PARAM_INT);
        $statement->bindValue(':message', $order['message'], \PDO::PARAM_STR);
        $statement->bindValue(':product_id', $product['id'], \PDO::PARAM_INT);
        $statement->bindValue(':user_logo', $userLogo['id'], \PDO::PARAM_INT);



        $statement->execute();
    }
}
