<?php
require_once 'AuthController.php';
require_once './model/Category.php';
require_once './utils/response.php';


class CategoryController
{
    private $categoryModel;
    private $response;
    public function __construct()
    {
        $this->categoryModel = new Category($GLOBALS['conn']);
        $this->response = new Response();
    }

    public function getAllCategories()
    {
        $json = $this->categoryModel->getAllCategories();
        $this->response->status(200)->json($json);
    }

    public function getCategory($id)
    {
        // $queryParams = isset($_GET) ? $_GET : [];
        $response = $this->categoryModel->getCategoryById($id);
        $this->response->status(200)->json($response);

    }

    public function getCategoryBrands($id)
    {
        $response = $this->categoryModel->getCategoryBrands($id);
        $this->response->status(200)->json($response);
    }

    // public function getCategoryProducts($id)
    // {
    //     $response = $this->categoryModel->getCategoryProducts($id);
    //     $this->response->status(200)->json($response);
    // }
    public function getCategoryProducts($id)
    {
        $brandIds = isset($_GET['brandIds']) ? explode(',', $_GET['brandIds']) : [];
        $response = $this->categoryModel->getCategoryProducts($id, $brandIds);
        $this->response->status(200)->json($response);
    }
    //not finished
    public function getProductsOfBrandAndCategory($categoryId, $brandId)
    {
        // echo $categoryId; 
        // echo $brandId; 
        $response = $this->categoryModel->getProductsOfBrandAndCategory($categoryId, $brandId);
        $this->response->status(200)->json($response);
    }
    public function createCategory()
    {
        // check token valid
        // AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->categoryModel->createCategory($data);
        $this->response->status(200)->json($response);
    }

    public function updateCategory($id)
    {
        // check token valid
        // AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->categoryModel->updateCategory($id, $data);
        $this->response->status(200)->json($response);
    }

    public function deleteCategory($id)
    {
        // check token valid
        // AuthController::validateToken();

        $response = $this->categoryModel->DeleteCategory($id);
        $this->response->status(200)->json($response);

    }
}
