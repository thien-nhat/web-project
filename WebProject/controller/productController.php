<?php
require_once 'AuthController.php';
require_once './model/Product.php';
require_once './utils/response.php';

class ProductController {
    private $productModel;
    private $response;

    public function __construct() {
        // $this->productModel = $productModel;
        $this->productModel = new Product($GLOBALS['conn']);
        $this->response = new Response();
    }
    // Filter function
    public function saleProducts() {
        echo 'Product';
        $json = $this->productModel->getSaleProducts();
        $this->response->status(200)->json($json);
    }

    public function bestProduct() {
        $json = $this->productModel->getWeeklyProducts();
        $this->response->status(200)->json($json);
    }

    public function topProduct() {
        $json = $this->productModel->getTopProducts();
        $this->response->status(200)->json($json);
    }

    public function newProduct() {
        $json = $this->productModel->getNewProducts();
        $this->response->status(200)->json($json);
    }

    public function getAllProducts() {
        // return $this->productModel->getAllProducts();
        // $json = $this->productModel->getAllProducts();
        // $this->response->status(200)->json($json);
        $queryParams = isset($_GET) ? $_GET : [];
        $page = isset($queryParams['page']) ? $queryParams['page'] : 1;
        $pageSize = isset($queryParams['pageSize']) ? $queryParams['pageSize'] : 10;
        $json = $this->productModel->getAllProducts($page, $pageSize);
        $this->response->status(200)->json($json);
    }

    public function getProduct($id) {
        $json = $this->productModel->getProductById($id);
        $this->response->status(200)->json($json);
    }

    public function createProduct() {
        // return $this->productModel->createProduct($data);
        // AuthController::validateToken();
        
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->productModel->createProduct($data);
        $this->response->status(200)->json($response);
    }

    public function createProductImage($id) {
        // return $this->productModel->createProduct($data);
        // AuthController::validateToken();
        
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->productModel->createProductImage($id, $data);
        $this->response->status(200)->json($response);
    }

    public function updateProduct($id) {
        // AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->productModel->updateProduct($id, $data);
        $this->response->status(200)->json($response);
    }

    public function deleteProduct($id)
    {
        // check token valid
        // AuthController::validateToken();
        
        $response = $this->productModel->deleteProduct($id);
        $this->response->status(200)->json($response);

    }
}
