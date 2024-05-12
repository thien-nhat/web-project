<?php

require_once './utils/response.php';
require_once './model/User.php';


class AuthController
{
    private static $algo = 'HS256';
    private static $type = 'JWT';
    private static $hash = 'sha256';

    private static function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    private static function generateToken(array $payload): string
    {
        $header = self::base64UrlEncode(json_encode(['alg' => self::$algo, 'typ' => self::$type]));

        $payload = self::base64UrlEncode(json_encode([
            'id' => $payload['id'],
            'email' => $payload['email'],
            'role' => $payload['role']
        ]));

        $signature = hash_hmac(self::$hash, "$header.$payload", $_ENV['SECRET_KEY'], true);
        $signature = self::base64UrlEncode($signature);

        return "$header.$payload.$signature";
    }
    private static function createSendToken(array $user, int $statusCode)
    {
        $response = new Response();
        $token = self::generateToken($user);
        // Return the token
        $response->status($statusCode)->json(['status' => 'success', 'token' => $token, 'data' => $user]);
    }
    private static function getToken():string {
        $response = new Response();
        $token = "";
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION'] !== '') {
            if (!preg_match("/^Bearer \S+$/", $_SERVER['HTTP_AUTHORIZATION']))
                $response->status(401)->json(array('message' => 'Invalid token'));
            $token = explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[1];
        }
        if (!$token) {
            $response->status(401)->json(array('error' => 'You are not logged in! Please log in to get access.'));
        }
        return $token;
    }
    private static function decodeToken(string $token): array|null
    {
        $payload = base64_decode(explode('.', $token)[1]);
        return json_decode($payload, true);
    }

    private static function validateHash($data, $hash): bool
    {
        return hash(self::$hash, $data) == $hash;
    }

    private static function genHash($data): string
    {
        return hash(self::$hash, $data);
    }
    public static function validateToken() : string
    {
        $response = new Response();
                $userModel = new User($GLOBALS['conn']);

        $token = self::getToken();
        $token_parts = explode('.', $token);
        if (count($token_parts) !== 3) {
            $response->status(401)->json(array('error' => 'Token not contain 3 parts.'));

        }
        $header = base64_decode($token_parts[0]);
        $payload = base64_decode($token_parts[1]);
        $signature = $token_parts[2];

        $header_data = json_decode($header, true);

        if (
            !isset($header_data['alg']) ||
            !isset($header_data['typ']) ||
            $header_data['alg'] !== self::$algo ||
            $header_data['typ'] !== self::$type
        ) {
            // echo "Wrong token header data\n";
            // return false;
            $response->status(401)->json(['error' => 'Wrong token header data']);

        }

        $header = self::base64UrlEncode($header);
        $payload = self::base64UrlEncode($payload);

        $valid_signature = hash_hmac(self::$hash, "$header.$payload", $_ENV['SECRET_KEY'], true);
        $valid_signature = self::base64UrlEncode($valid_signature);

        // return ($signature === $valid_signature);
        if ($signature !== $valid_signature) {
            $response->status(401)->json(['error' => 'Invalid signature']);
        }
        // Decode the token
        $decodedToken = self::decodeToken($token);

        // Retrieve the user from the database using the user ID from the decoded token
        $userId = $decodedToken['id'];
        $freshUser = $userModel->getUserById($userId);

        // Check if the user exists
        if (!$freshUser) {
            $response->status(401)->json(['error' => 'The user belonging to this token does no longer exist.']);
        }
        return $userId;
    }
    public static function restrictTo(...$roles)
    {
        $response = new Response();
        $token = self::getToken();
        // Decode the token
        $decodedToken = self::decodeToken($token);

        // Retrieve the user from the database using the user ID from the decoded token
        $userRole = $decodedToken['role'];
        if (!in_array($userRole, $roles)) {
            $response->status(403)->json(['error' => 'You do not have permission to perform this action.']);
        }
    }

    
    public static function login()
    {
        $response = new Response();
        $userModel = new User($GLOBALS['conn']);

        $body = json_decode(file_get_contents('php://input'), true);
        if (empty($body['email']) || empty($body['password'])) {
            $response->status(400)->json(['error' => 'Email and password are required']);
        }

        // Retrieve user by email
        $user = $userModel->getUserByEmail($body['email']);
        if (!$user || !self::validateHash($body['password'], $user['password'])) {
            $response->status(401)->json(['error' => 'Invalid email or password']);
        }
        self::createSendToken($user, 200);
    }
    public static function register() {
        $response = new Response();
        $userModel = new User($GLOBALS['conn']);

        $body = json_decode(file_get_contents('php://input'), true);
        if (empty($body['email']) || empty($body['password'])) {
            $response->status(400)->json(['error' => 'Email and password are required']);
        }

        // Check if the user already exists
        $user = $userModel->getUserByEmail($body['email']);
        if ($user) {
            $response->status(400)->json(['error' => 'User already exists']);
        }

        // Hash the password
        $body['password'] = self::genHash($body['password']);

        // Create the user
        $result = $userModel->createUser($body);
        if (!$result) {
            $response->status(500)->json(['error' => 'An error occurred while creating the user']);
        }

        $user = $userModel->getUserByEmail($body['email']);
        self::createSendToken($user, 201);
    }
}