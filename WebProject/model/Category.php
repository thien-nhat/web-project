<?php

class Category
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllCategories()
    {
        $result = mysqli_query($this->conn, "SELECT * FROM category");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function getCategoryById1($categoryId)
    {
        $stmt = $this->conn->prepare("CALL getCategory(?)");
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        // Lấy danh sách các thương hiệu
        $brands = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Di chuyển đến tập kết quả tiếp theo
        $stmt->next_result();

        // Lấy danh sách các sản phẩm
        if ($result = $stmt->get_result()) {
            $products = $result->fetch_all(MYSQLI_ASSOC);
        }
        return array('brands' => $brands, 'products' => $products);
    }
    public function getCategoryById($categoryId)
    {
        $query = "SELECT * FROM category WHERE id = $categoryId";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function createCategory($data)
    {
        // Lấy kích thước của bảng category
        $getSizeQuery = "SELECT COUNT(*) AS size FROM category";
        $sizeResult = mysqli_query($this->conn, $getSizeQuery);
        $sizeData = mysqli_fetch_assoc($sizeResult);
        $size = $sizeData['size'];

        // Tạo ID mới bằng cách thêm 1 vào size
        $data['id'] = $size + 1;

        // Tạo chuỗi trường và giá trị cho câu truy vấn INSERT
        $fields = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";

        // Tạo câu truy vấn INSERT
        $query = "INSERT INTO category ($fields) VALUES ($values)";

        // Thực thi câu truy vấn và trả về kết quả
        return mysqli_query($this->conn, $query);
    }


    public function updateCategory($id, $data)
    {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = '$value', ";
        }
        $updates = rtrim($updates, ', ');
        $query = "UPDATE category SET $updates WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    public function deleteCategory($id)
    {
        $query = "DELETE FROM category WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    // public function getCategoryProducts($id)
    // {
    //     // $query = "SELECT id, name, price FROM product WHERE category_id = $id";
    //     // $result = mysqli_query($this->conn, $query);
    //     // return mysqli_fetch_all($result, MYSQLI_ASSOC);
    //     $query = "SELECT p.id, p.name, p.price, pi.url, p.discount
    //           FROM product p 
    //           LEFT JOIN productimage pi ON p.id = pi.product_id 
    //           WHERE p.category_id = $id 
    //           GROUP BY p.id";
    //     $result = mysqli_query($this->conn, $query);
    //     return mysqli_fetch_all($result, MYSQLI_ASSOC);
    // }
    public function getCategoryProducts($id, $brandIds = [])
    {
        $brandIdsCondition = '';
        if (!empty($brandIds)) {
            $brandIdsCondition = 'AND p.brand_id IN (' . implode(',', $brandIds) . ')';
        }
        $query = "SELECT p.id, p.name, p.price, pi.url, p.discount
              FROM product p 
              LEFT JOIN productimage pi ON p.id = pi.product_id 
              WHERE p.category_id = $id $brandIdsCondition
              GROUP BY p.id";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function getCategoryBrands($id)
    {
        $query = "SELECT b.id, b.name FROM brand_category bc JOIN brand b ON bc.brand_id = b.id WHERE bc.category_id = $id";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function getProductsOfBrandAndCategory($categoryId, $brandId)
    {
        $query = "SELECT p.id, p.name, p.price, pi.url 
              FROM product p 
              LEFT JOIN productimage pi ON p.id = pi.product_id 
              WHERE p.category_id = $categoryId AND p.brand_id = $brandId
              GROUP BY p.id";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
