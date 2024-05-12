<?php
require_once 'AuthController.php';
require_once './model/Review.php';
require_once './utils/response.php';


class ReviewController
{
    private $reviewModel;
    private $response;
    public function __construct()
    {
        $this->reviewModel = new Review($GLOBALS['conn']);
        $this->response = new Response();
    }

    public function getAllReviews()
    {
        $queryParams = isset($_GET) ? $_GET : [];
        $json = $this->reviewModel->getAllReviews();
        $this->response->status(200)->json($json);
    }

    public function getReview($productId)
    {
        $data = $this->reviewModel->getReviewByProductId($productId);
        $response = array(
            "status" => "success",
            "result" => count($data),
            "data" => $data
        );

        $this->response->status(200)->json($response);

    }

    public function createReview($productId)
    {
        // check token valid
        $userid = AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->reviewModel->createUserReview($userid, $productId, $data);
        $this->response->status(200)->json($response);
    }

    public function updateReview($id)
    {
        // check token valid
        AuthController::validateToken();

        $data = json_decode(file_get_contents('php://input'), true);
        $response = $this->reviewModel->updateReview($id, $data);
        $this->response->status(200)->json($response);
    }

    public function deleteReview($id)
    {
        // check token valid
        AuthController::validateToken();

        $response = $this->reviewModel->deleteReview($id);
        $this->response->status(200)->json($response);

    }
}
