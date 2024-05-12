<?php
require_once 'AuthController.php';
require_once './model/AskSupport.php';
require_once './utils/response.php';


class AskSupportController 
{
    private $askSupportModel;
    private $response;
    public function __construct()
    {
        $this->askSupportModel = new AskSupport($GLOBALS['conn']);
        $this->response = new Response();
    }

    public function selectAll()
    {
        // check token valid
        // AuthController::validateToken();
        // check valid role
        // AuthController::restrictTo('admin');

        $json = $this->askSupportModel->selectAll();
        $this->response->status(200)->json($json);
    }

    public function createPost()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->askSupportModel->createPost($data);
        $this->response->status(200)->json($response);
    }
}