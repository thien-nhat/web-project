<?php
require_once 'AuthController.php';
require_once './model/Order.php';
require_once './utils/response.php';


class OrderController 
{
    private $OrderModel;
    private $response;
    public function __construct()
    {
        $this->OrderModel = new Order($GLOBALS['conn']);
        $this->response = new Response();
    }

    public function getAllOrders()
    {
        $queryParams = isset($_GET) ? $_GET : [];
        $json = $this->OrderModel->getAllOrders();
        $response = array(
            "status" => "success",
            "result" => count($json),
            "data" => $json
        );
        $this->response->status(200)->json($json);
    }

    public function getOrder($id)
    {
        $data =  $this->OrderModel->getOrderById($id);
        $response = array(
            "status" => "success",
            "result" => count($data),
            "data" => $data
        );
        $this->response->status(200)->json($response);
    }
    public function getMyOrderId()
    {
        $id = AuthController::validateToken();
        $data = $this->OrderModel->getOrderIdByUserId($id);
        $response = array(
            "status" => "success",
            "result" => count($data),
            "data" => $data
        );
        $this->response->status(200)->json($response);
    }
    public function createOrder()
    {
        // check token valid
        $id = AuthController::validateToken();
        // $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->OrderModel->createUserOrder($id);
        if($response["status"] === 'success') {
            $this->response->status(200)->json($response);
        }else {
            $this->response->status(400)->json($response);
        }
    }

    public function updateOrder($id)
    {
        // check token valid
        // AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->OrderModel->updateOrder($id, $data);
        $this->response->status(200)->json($response);
    }

    public function deleteOrder($id)
    {
        // check token valid
        AuthController::validateToken();
        // $data = json_decode(file_get_contents('php://input'), true);
        
        $response = $this->OrderModel->deleteOrder($id);
        $this->response->status(200)->json($response);

    }
}
