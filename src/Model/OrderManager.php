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
    public const STATUSES = [
        'NEW' => "Nouvelle commande",
        'PROCESSING' => "En cours de traitement",
        'PROCESSED' => "Traitée",
        'SENT' => "Expédiée",
    ];

    public function __construct()
    {
        parent::__construct(self:: TABLE);
    }
    public function updateOrder(array $order): bool
    {
        // prepared request
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `status`= :status, `firstname`= :firstname, 
        `lastname`= :lastname, `email`= :email, `phone`= :phone, `company_name`= :companyName, `address`= :address,
         `city`= :city, `postcode`= :postcode, `size`= :size, `quantity`= :quantity, `message`= :message, 
         `user_logo`= :user_logo, WHERE id=:id");
        $statement->bindValue('id', $order['id'], \PDO::PARAM_INT);
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
        $statement->bindValue(':user_logo', $order['userLogo'], \PDO::PARAM_STR);

        return $statement->execute();
    }
    public function selectAllJoinProduct(): array
    {
        return $this->pdo->query("SELECT o.id, o.firstname, o.lastname, o.email, o.phone, 
            o.company_name, o.address, o.city, postcode, o.size, o.quantity, o.message, o.product_id, o.user_logo, 
            o.status, p.name product_name FROM " . self::TABLE . ' o JOIN product p ON p.id=o.product_id;')->fetchAll();
    }
    public function selectByIdJoinProduct(int $id): array
    {
        $statement = $this->pdo->prepare("SELECT o.firstname, o.lastname, 
        o.email, o.phone, o.company_name, o.address, o.postcode, o.city, 
        o.size, o.quantity, o.message, o.product_id, o.user_logo, o.status, 
        p.name product_name, p.reference product_reference FROM " . self::TABLE . " o 
        JOIN product p ON p.id=o.product_id WHERE o.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
    public function saveOrder(array $order, $product)
    {
        $query = "INSERT INTO " . self::TABLE .
            " (`firstname`, `lastname`, `email`, `phone`, `company_name`, 
            `address`, `city`, `postcode`, `size`, `quantity`, `message`, `product_id`, `user_logo`, `status`) 
            VALUES 
            (:firstname, :lastname, :email, :phone, :company_name, 
            :address, :city, :postcode, :size, :quantity, :message, 
            :product_id, :user_logo, '" . self::STATUSES['NEW'] . "')";
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
        $statement->bindValue(':user_logo', $order['userLogo'], \PDO::PARAM_STR);



        $statement->execute();
    }
}
