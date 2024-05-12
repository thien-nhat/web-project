<?php

class AskSupport {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function selectAll() {
        $query = "SELECT * FROM ask_support";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function createPost($data) {
        $fields = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";
        $query = "INSERT INTO ask_support ($fields) VALUES ($values)";
        return mysqli_query($this->conn, $query);
    }
}
