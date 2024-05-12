<?php
require_once 'AuthController.php';
require_once './model/User.php';
require_once './utils/response.php';


class UserController 
{
    private $userModel;
    private $response;
    public function __construct()
    {
        $this->userModel = new User($GLOBALS['conn']);
        $this->response = new Response();
    }

    public function getAllUsers()
    {
        // check token valid
        // AuthController::validateToken();
        // check valid role
        // AuthController::restrictTo('admin');

        $queryParams = isset($_GET) ? $_GET : [];
        $json = $this->userModel->getAllUsers($queryParams);
        $this->response->status(200)->json($json);
    }

    public function getUserById($id)
    {
        $response = $this->userModel->getUserById($id);
        $this->response->status(200)->json($response);

    }

    public function createUser()
    {
        // check token valid
        AuthController::validateToken();
        
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->userModel->createUser($data);
        $this->response->status(200)->json($response);
    }

    public function updateUser($id)
    {
        // check token valid
        AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->userModel->updateUser($id, $data);
        $this->response->status(200)->json($response);
    }

    public function deleteUser($id)
    {
        // check token valid
        AuthController::validateToken();
        
        $response = $this->userModel->deleteUser($id);
        $this->response->status(200)->json($response);

    }
}
