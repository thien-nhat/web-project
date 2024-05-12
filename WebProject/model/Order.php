<?php

class Order
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function getAllOrders()
    {
        $result = mysqli_query($this->conn, "SELECT * FROM `order`");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getOrderById($id)
    {
        $sql = "SELECT 
        p.name AS productName, 
        (
            SELECT url 
            FROM productImage 
            WHERE product_id = p.id 
            ORDER BY id LIMIT 1
        ) AS imageUrl, 
        op.quantity,
        op.price
        FROM 
        `order` o
        INNER JOIN 
        order_product op ON o.id = op.orderId
        INNER JOIN 
        product p ON op.productId = p.id
        WHERE o.id = '$id'
        ";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function getOrderIdByUserId($userId)
    {
        $sql = "SELECT id AS orderId, order_date
        FROM `order`
        WHERE user_id = $userId;
        ";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function createUserOrder($userId)
    {
        $id = uniqid();
        // $fields = implode(', ', array_keys($data));
        // $values = "'" . implode("', '", $data) . "'";
        // $query = "INSERT INTO order ($fields) VALUES ($values)";
        // return mysqli_query($this->conn, $query);
        $isSuccess = true; // Initialize isSuccess variable
        $query = "CALL createUserOrder('$userId', '$id', @isSuccess)"; // Use @isSuccess as the third argument
        mysqli_query($this->conn, $query);

        // Retrieve the value of @isSuccess after the call
        $result = mysqli_query($this->conn, "SELECT @isSuccess");
        $row = mysqli_fetch_assoc($result);
        $isSuccess = $row['@isSuccess'];
        $isSuccess = $isSuccess == 0 ? false : true;
        
        if($isSuccess) {
            $response = array(
                "status" => "success",
                "id" => $id,
                "isSuccess" => $isSuccess
            );
        } else {
            $response = array(
                "status" => "fail",
                "message" => "Cart is empty or user does not exist"
            );
        }
        
        return $response; 

    }

    public function updateOrder($id, $data)
    {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = '$value', ";
        }
        $updates = rtrim($updates, ', ');
        $query = "UPDATE order SET $updates WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    public function deleteOrder($id)
    {
        $query = "DELETE FROM order WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }
}
