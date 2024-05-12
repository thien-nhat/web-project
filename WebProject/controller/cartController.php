<?php
require_once 'AuthController.php';
require_once './model/Cart.php';
require_once './utils/response.php';


class CartController
{
    private $CartModel;
    private $response;
    public function __construct()
    {
        $this->CartModel = new Cart($GLOBALS['conn']);
        $this->response = new Response();
    }

    public function getAllCarts()
    {
        // $queryParams = isset($_GET) ? $_GET : [];
        $json = $this->CartModel->getAllCarts();
        $this->response->status(200)->json($json);
    }

    public function getCart($id)
    {
        $response = $this->CartModel->getCartByUserId($id);
        $this->response->status(200)->json($response);

    }
    public function getMyCart()
    {
        $id = AuthController::validateToken();
        $response = $this->CartModel->getMyCart($id);
        $this->response->status(200)->json($response);

    }

    public function createCart()
    {
        // check token valid
        $data = json_decode(file_get_contents('php://input'), true);
        $data["userId"] = AuthController::validateToken();
        $response = $this->CartModel->createUserCart($data);
        if($response == true) {
            $this->response->status(200)->json(array("status" => "success", "message" => "Cart created successfully"));
        } else {
            $this->response->status(400)->json($response);
        }
    }

    public function updateCart($id)
    {
        // check token valid
        // AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->CartModel->updateUserCart($id, $data);
        $this->response->status(200)->json($response);
    }

    public function deleteCart($id)
    {
        // check token valid
        $userId = AuthController::validateToken();
        $response = $this->CartModel->deleteCart($userId, $id);
        $this->response->status(200)->json($response);
    }
}
