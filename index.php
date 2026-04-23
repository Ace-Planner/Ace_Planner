<?php
// All requests come through this file

// Always return JSON
header("Content-Type: application/json");

// Include response helper
require_once __DIR__ . "/utils/response.php";

// Get request path (e.g., /tasks, /auth)
$requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Remove base folder path
$scriptName = dirname($_SERVER["SCRIPT_NAME"]);
$path = str_replace($scriptName, "", $requestUri);
$path = trim($path, "/");

// Route request to correct file
switch ($path) {

    case "authentication":
        require __DIR__ . "/routes/authentication.php";
        break;

    case "tasks":
        require __DIR__ . "/routes/tasks.php";
        break;

    case "courses":
        require __DIR__ . "/routes/courses.php";
        break;

    case "calendar":
        require __DIR__ . "/routes/calendar.php";
        break;

    case "notification":
        require __DIR__ . "/routes/notification.php";
        break;

    case "preferences":
        require __DIR__ . "/routes/preferences.php";
        break;

    default:
        jsonResponse(404, ["message" => "Route not found"]);
}