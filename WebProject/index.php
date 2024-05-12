<?php
include_once './utils/response.php';
include_once './utils/env.php';
include_once './routes/CoreRoute.php';
include_once './routes/configRoutes.php';
include_once 'utils/exceptionError.php';

// CORS policy
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Handle exception error
set_exception_handler('exceptionHandler');


// Tạo một thực thể DotEnv với đường dẫn tới file .env
$dotenv = new DotEnv(__DIR__ . '/.env');

// Load các biến môi trường từ file .env
$dotenv->load();

// Define your database connection
$conn = mysqli_connect($_ENV['DB_HOST'],  $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// $response = new Response();

// Extract the path from the request URI
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path ("/WebProject") if present
$basePath = '/WebProject';
$pathWithoutBase = (strpos($path, $basePath) === 0) ? substr($path, strlen($basePath)) : $path;

// Switch based on the path without the base
// switch ($pathWithoutBase) {
//     case '/api/user':
//         $routesFile = 'routes/userRoutes.php';
//         break;
//     case '/api/review':
//         $routesFile = 'routes/reviewRoutes.php';
//         break;
//     case '/api/product':
//             $routesFile = 'routes/productRoutes.php';
//             break;   
//     default:
//         // Handle other routes or return an error
//         $response->status(400)->json(array('error' => 'Invalid route'));
// }

// // Load the routes file
// $routes = require_once $routesFile;
// // Call the routes with the base path
// $routes();

// Create an instance of the Route class
$routeHandler = new Route();
// MiddlewareManager::register('api/user')->isProtect()->hasRole('admin');

// Handle the route using the defined logic
$routeHandler->handleRoute($pathWithoutBase);
// Close the database connection
mysqli_close($conn);
