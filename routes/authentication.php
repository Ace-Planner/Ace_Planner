<?php
// Include the AuthController file so we can use its functions
require_once __DIR__ . "/../controllers/AuthController.php";

// Create a new AuthController object
$controller = new AuthController();

// Get the HTTP request method (GET, POST, etc.)
$method = $_SERVER["REQUEST_METHOD"];

// Get the action from the URL query string
// Example: /auth?action=login
$action = $_GET["action"] ?? "";

// =========================
// ROUTING LOGIC
// =========================

// If the request is POST and action=register,
// call the register() function in AuthController
if ($method === "POST" && $action === "register") {
    $controller->register();

// If the request is POST and action=login,
// call the login() function
} elseif ($method === "POST" && $action === "login") {
    $controller->login();

// If the request is POST and action=logout,
// call the logout() function
} elseif ($method === "POST" && $action === "logout") {
    $controller->logout();

// If the request is GET and action=me,
// call the me() function to return the logged-in user's info
} elseif ($method === "GET" && $action === "me") {
    $controller->me();

// If none of the above match, return an error
} else {
    jsonResponse(405, ["message" => "Method not allowed"]);
}