<?php
include_once 'utils/response.php';

// Define a function to handle uncaught exceptions
// Define a function to handle uncaught exceptions
function exceptionHandler($exception) {
    // Determine appropriate status code based on the exception type
    $statusCode = ($exception instanceof mysqli_sql_exception) ? 500 : 400;
    
    // Prepare response payload
    $response = [
        'status' => 'fail',
        'error' => $exception->getMessage(),
    ];
    
    // Create a Response object
    $responseObj = new Response();
    
    // Set the HTTP status code and return the response as JSON
    $responseObj->status($statusCode)->json($response);
}

