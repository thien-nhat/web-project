<?php

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Getter and setter methods for user properties go here...

    // public function getAllUsers($page, $limit)
    // {
    //     $result = mysqli_query($this->conn, "SELECT * FROM user");
    //     return mysqli_fetch_all($result, MYSQLI_ASSOC);
    // }
    public function getAllUsers($queryParams)
    {
        $sql = "SELECT * FROM user";


        // Sort by fields
        if (isset($queryParams['sort'])) {
            $sortBy = str_replace(',', ' ', $queryParams['sort']);
            $sql .= ' ORDER BY ' . $sortBy;
        } else {
            $sql .= ' ORDER BY id ASC';
        }

        // Paging
        $page = isset($queryParams['page']) ? (int) $queryParams['page'] : 1;
        $limit = isset($queryParams['limit']) ? (int) $queryParams['limit'] : 10;
        $offset = ($page - 1) * $limit;
        $sql .= ' LIMIT ' . $limit;
        $sql .= ' OFFSET ' . $offset;

        $result = mysqli_query($this->conn, $sql);


        if ($result === false) {
            die(mysqli_error($this->conn)); // Handle the error appropriately
        }

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getUserById($id)
    {
        $result = mysqli_query($this->conn, "SELECT id, username, email, role FROM user WHERE id = $id");
        return mysqli_fetch_assoc($result);
    }
    public function getUserByEmail($email)
    {

        $result = mysqli_query($this->conn, "SELECT * FROM user WHERE email = '{$email}'");

        return mysqli_fetch_assoc($result);
    }
    public function createUser($data)
    {
        $data['role'] = 'user'; // Add this line to set the role to 'user'
        $fields = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";
        $query = "INSERT INTO user ($fields) VALUES ($values)";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    public function updateUser($id, $data)
    {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = '$value', ";
        }
        $updates = rtrim($updates, ', ');
        $query = "UPDATE user SET $updates WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM user WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }
}
