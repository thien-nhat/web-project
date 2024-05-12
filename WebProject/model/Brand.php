<?php

class Brand {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function getAllBrand() {
        $result = mysqli_query($this->conn, "SELECT * FROM brand");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getBrandById($id) {
        $result = mysqli_query($this->conn, "SELECT * FROM brand WHERE id = $id");
        return mysqli_fetch_assoc($result);
    }

    public function createBrand($data)
    {
        // Lấy kích thước của bảng category
        $getSizeQuery = "SELECT COUNT(*) AS size FROM brand";
        $sizeResult = mysqli_query($this->conn, $getSizeQuery);
        $sizeData = mysqli_fetch_assoc($sizeResult);
        $size = $sizeData['size'];

        // Tạo ID mới bằng cách thêm 1 vào size
        $data['id'] = $size + 1;

        // Tạo chuỗi trường và giá trị cho câu truy vấn INSERT
        $fields = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";

        // Tạo câu truy vấn INSERT
        $query = "INSERT INTO brand ($fields) VALUES ($values)";

        // Thực thi câu truy vấn và trả về kết quả
        return mysqli_query($this->conn, $query);
    }


    public function updateBrand($id, $data) {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = '$value', ";
        }
        $updates = rtrim($updates, ', ');
        $query = "UPDATE brand SET $updates WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    public function deleteBrand($id) {
        $query = "DELETE FROM brand WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }
}
