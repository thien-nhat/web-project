<?php

class Review
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Getter and setter methods for review properties go here...

    public function getAllReviews()
    {
        $result = mysqli_query($this->conn, "SELECT * FROM review");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getReviewByProductId($productId)
    {
        // $result = mysqli_query($this->conn, "SELECT * FROM review WHERE product_id = $productId");
        $query = "SELECT review.*, user.username 
              FROM review 
              INNER JOIN user ON review.user_id = user.id 
              WHERE review.product_id = $productId";
        $result = mysqli_query($this->conn, $query);

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function createUserReview($userid, $productId, $data)
    {
        $data['user_id'] = $userid;
        $data['product_id'] = $productId;
        $fields = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";
        $query = "INSERT INTO review ($fields) VALUES ($values)";
        return mysqli_query($this->conn, $query);
    }

    public function updateReview($id, $data)
    {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = '$value', ";
        }
        $updates = rtrim($updates, ', ');
        $query = "UPDATE review SET $updates WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    public function deleteReview($id)
    {
        $query = "DELETE FROM review WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }
}
