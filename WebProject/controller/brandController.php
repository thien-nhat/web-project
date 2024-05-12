<?php
require_once 'AuthController.php';
require_once './model/Brand.php';
require_once './utils/response.php';


class BrandController
{
    private $BrandModel;
    private $response;
    public function __construct()
    {
        $this->BrandModel = new Brand($GLOBALS['conn']);
        $this->response = new Response();
    }

    public function getAllBrands()
    {
        $json = $this->BrandModel->getAllBrand();
        $this->response->status(200)->json($json);
    }

    public function getBrand($id)
    {
        $response = $this->BrandModel->getBrandById($id);
        $this->response->status(200)->json($response);

    }

    public function createBrand()
    {
        // check token valid
        $data = json_decode(file_get_contents('php://input'), true);
        // $data["userId"] = AuthController::validateToken();
        $response = $this->BrandModel->createBrand($data);
        if($response == true) {
            $this->response->status(200)->json(array("status" => "success", "message" => "Brand created successfully"));
        } else {
            $this->response->status(400)->json($response);
        }
    }

    public function updateBrand($id)
    {
        // check token valid
        // AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->BrandModel->updateBrand($id, $data);
        $this->response->status(200)->json($response);
    }

    public function deleteBrand($id)
    {
        // check token valid
        // $userId = AuthController::validateToken();
        $response = $this->BrandModel->deleteBrand($id);
        $this->response->status(200)->json($response);
    }
}
