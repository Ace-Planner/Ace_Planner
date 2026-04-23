<?php
// Include NotificationController
require_once __DIR__ . "/../controllers/NotificationControl.php";

// Create controller object
$controller = new NotificationControl();

// Get request method
$method = $_SERVER["REQUEST_METHOD"];

// Optional action query string, example: /notifications?action=read&id=2
$action = $_GET["action"] ?? "";

// Handle routing
if ($method === "GET") {
    // Get all notifications
    $controller->getNotifications();

} elseif ($method === "POST" && $action === "create") {
    // Create new notification
    $controller->createNotification();

} elseif ($method === "POST" && $action === "read") {
    // Mark notification as read
    $controller->markAsRead();

} elseif ($method === "DELETE") {
    // Delete notification
    $controller->deleteNotification();

} else {
    // Return error if unsupported method/action
    jsonResponse(405, ["message" => "Method not allowed"]);
}