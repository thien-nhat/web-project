<?php
require_once './controller/AuthController.php';
require_once './utils/response.php';

class Route
{
    private $response;
    public function __construct()
    {
        $this->response = new Response();
    }

    public function handleRoute($url)
    {

        global $routes;

        unset($routes['default_controller']);

        $url = trim($url, '/');

        if (empty($url)):
            $url = '/';
        endif;

        $handleUrl = $url;
        $method = $_SERVER['REQUEST_METHOD']; // Assuming this gives you the HTTP method (GET, POST, PUT, DELETE)

        if (!empty($routes)) {
            foreach ($routes as $routeKey => $routeConfig) {
                $partsRouteKey = explode('/', $routeKey);
                $partsCurrentRoute = explode('/', $url);
                // echo $routeKey . " AND " . $url .  "<br>";

                if (count($partsRouteKey) != count($partsCurrentRoute))
                    continue;
                // Check if the route has a parameter ':id'
                $pattern = str_replace(':id', '(.+)', $routeKey);
                // Add value of this pattern

                if (empty($routeConfig[$method]))
                    continue;
                else
                    $value = $routeConfig[$method];

                if (preg_match('~' . $pattern . '~', $url)) {
                    // Replace the parameter ':id' with the captured value ($1)
                    $handleUrl = preg_replace('~' . $pattern . '~', $value, $url);
                    break;
                }
            }
        }
        $this->callControllerMethod($handleUrl);


    }

    private function callControllerMethod($url)
    {
        // Tách controller, action, và method từ URL
        $urlParts = explode('/', $url);
        $controller = $urlParts[0];

        // Kiểm tra xem controller có tồn tại không
        $controllerFileName = "./controller/{$controller}Controller.php";

        if (file_exists($controllerFileName)) {
            // Include file controller
            include_once ($controllerFileName);

            // Tạo đối tượng controller
            $className = $controller . 'Controller';
            $controllerInstance = new $className();

            // Xác định action tương ứng với method
            $action = $urlParts[1];

            // Kiểm tra xem action có tồn tại không
            if (method_exists($controllerInstance, $action)) {
                // Gọi action với method cụ thể và các tham số từ URL (nếu có)
                $params = count($urlParts) > 2 ? array_slice($urlParts, 2) : [];
                call_user_func_array([$controllerInstance, $action], $params);
            } else {
                // Xử lý khi action không tồn tại
                echo "Action not found!";
                $this->response->status(404)->json("Action not found!");

            }
        } else {
            // Xử lý khi controller không tồn tại
            if ($url !== '/') {
                $url = '/' . $url;
            }
            $this->response->status(404)->json([
                "status" => "fail",
                "error" => $url . " not found !"
            ]);
        }
    }
}

