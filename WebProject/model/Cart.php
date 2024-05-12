<?php

class Cart
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function getAllCarts()
    {
        $result = mysqli_query($this->conn, "SELECT * FROM cart");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getCartByUserId($id)
    {
        $result = mysqli_query($this->conn, "SELECT * FROM cart WHERE userId = $id");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function getMyCart($id)
    {
        $result = mysqli_query($this->conn, "SELECT * FROM cart WHERE userId = $id");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function createCart($data)
    {
        print_r($data);
        $fields = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";
        $query = "INSERT INTO cart ($fields) VALUES ($values)";
        return mysqli_query($this->conn, $query);
    }
    function createUserCart($data)
    {
        $userId = !empty($data["userId"]) ? $data["userId"] : NULL;
        $color = !empty($data["color"]) ? $data["color"] : NULL;
        $size = !empty($data["size"]) ? $data["size"] : NULL;
        $productId = !empty($data["productId"]) ? $data["productId"] : NULL;
        $quantity = !empty($data["quantity"]) ? $data["quantity"] : 1;

        $stmt = $this->conn->prepare("CALL addCart(?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $userId, $color, $size, $productId, $quantity);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc()['result'];
    }
    function updateUserCart1($data)
    {
        // May be don't necessary
        $userId = !empty($data["userId"]) ? $data["userId"] : NULL;
        $color = !empty($data["color"]) ? $data["color"] : NULL;
        $size = !empty($data["size"]) ? $data["size"] : NULL;
        $productId = !empty($data["productId"]) ? $data["productId"] : NULL;
        $quantity = !empty($data["quantity"]) ? $data["quantity"] : NULL;

        $stmt = $this->conn->prepare("CALL updateCart(?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $userId, $color, $size, $productId, $quantity);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc()['result'];
    }
    public function updateUserCart($userId, $data)
    {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = '$value', ";
        }
        $updates = rtrim($updates, ', ');
        $productId = $data['productId'];
        $query = "UPDATE cart SET $updates WHERE userId = $userId AND productId = $productId";
        return mysqli_query($this->conn, $query);
    }
    
    public function deleteCart($userId, $productId)
    {
        $query = "DELETE FROM cart WHERE userId = $userId AND productId = $productId";
        return mysqli_query($this->conn, $query);
    }
}
