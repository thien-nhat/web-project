<?php

class Product
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Getter and setter methods for Product properties go here...

    public function getSaleProducts()
    {
        $query = "SELECT p.id, p.name, p.discount, pi.url AS image
        FROM (
            SELECT *
            FROM product
            ORDER BY discount DESC
            LIMIT 3
        ) AS p
        LEFT JOIN (
            SELECT product_id, MIN(url) AS url
            FROM productImage
            GROUP BY product_id
        ) AS pi ON p.id = pi.product_id;
        ";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function getNewProducts()
    {
        $query = "SELECT p.id, p.name, pi.url AS image
        FROM (
            SELECT *
            FROM product
            ORDER BY created_at DESC
            LIMIT 3
        ) AS p
        LEFT JOIN (
            SELECT product_id, MIN(url) AS url
            FROM productImage
            GROUP BY product_id
        ) AS pi ON p.id = pi.product_id;
        ";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function getTopProducts()
    {
        $query = "SELECT
                    op.productId,
                    SUM(op.quantity) AS total_quantity_all_time,
                    (
                        SELECT
                            p.name
                        FROM
                            product p
                        WHERE
                            p.id = op.productId
                    ) AS product_name,
                    (
                        SELECT
                            GROUP_CONCAT(pi.url)
                        FROM
                            productImage pi
                        WHERE
                            pi.product_id = op.productId
                    ) AS product_images
                FROM
                    order_product op
                GROUP BY
                    op.productId
                ORDER BY
                    total_quantity_all_time DESC
                LIMIT 3
                ";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function getWeeklyProducts()
    {
        $query = "
            SELECT 
                op.productId, 
                p.name AS product_name, 
                SUM(op.quantity) AS total_quantity_week, 
                (SELECT url FROM productImage pi WHERE pi.product_id = p.id ORDER BY pi.id LIMIT 1) AS product_image
            FROM 
                order_product op
            JOIN 
                (
                    SELECT id
                    FROM `order`
                    WHERE WEEK(order_date) = WEEK(CURRENT_DATE()) AND YEAR(order_date) = YEAR(CURRENT_DATE())
                ) o ON op.orderId = o.id
            JOIN 
                product p ON op.productId = p.id
            GROUP BY 
                op.productId
            ORDER BY 
                total_quantity_week DESC
            LIMIT 3 
        ";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    // public function getAllProducts()
    // {
    //     $result = mysqli_query($this->conn, "CALL GetAllProducts()");
    //     $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //     // return $products;
    //     foreach ($products as $i => $product) {
    //         $products[$i]['images'] = explode(',', $product['images']);
    //     }

    //     return $products;
    // }
    public function getAllProducts($page = 1, $pageSize = 10)
    {
        $offset = ($page - 1) * $pageSize;
        $query = "CALL GetAllProducts($pageSize, $offset)";
        $result = mysqli_query($this->conn, $query);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($products as $i => $product) {
            $products[$i]['images'] = explode(',', $product['images']);
        }
        return $products;
    }
    public function getProductById($id)
    {
        switch ($id) {
            case 'flash-sale':
                return $this->getSaleProducts();
            case 'best-seller':
                return $this->getWeeklyProducts();
            case 'top-product':
                return $this->getTopProducts();
            case 'new-product':
                return $this->getNewProducts();
            default:
                $stmt = $this->conn->prepare("CALL GetProduct(?)");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                foreach ($result as $i => $product) {
                    $result[$i]['images'] = explode(',', $product['images']);
                }
        }
        return $result;
    }


    public function createProduct($data)
    {
        $fields = implode(', ', array_keys($data));
        $values = "'" . implode("', '", $data) . "'";
        $query = "INSERT INTO product ($fields) VALUES ($values)";
        return mysqli_query($this->conn, $query);
    }
    public function createProductImage($product_id, $urls)
    {
        $num_urls = count($urls);

        // Tạo câu lệnh INSERT VALUES cho số lượng URL
        $insert_values = [];
        for ($i = 0; $i < $num_urls; $i++) {
            $insert_values[] = "($product_id, '{$urls[$i]}')";
        }

        // Chuyển mảng các giá trị INSERT VALUES thành một chuỗi
        $values_string = implode(', ', $insert_values);

        // Tạo câu lệnh SQL INSERT
        $query = "INSERT INTO productimage (product_id, url) VALUES $values_string";
        // Thực thi câu lệnh INSERT
        return mysqli_query($this->conn, $query);
    }

    public function updateProduct($id, $data)
    {
        $updates = '';
        foreach ($data as $key => $value) {
            $updates .= "$key = '$value', ";
        }
        $updates = rtrim($updates, ', ');
        $query = "UPDATE product SET $updates WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM product WHERE id = $id";
        return mysqli_query($this->conn, $query);
    }
}
