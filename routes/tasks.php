<?php
require_once __DIR__ . "/../controllers/TasksControl.php";

$controller = new TasksControl();
$method = $_SERVER["REQUEST_METHOD"];

// Decide what to do based on HTTP method
if ($method === "GET") {
    $controller->getTasks();
} elseif ($method === "POST") {
    $controller->createTask();
} else {
    jsonResponse(405, ["message" => "Method not allowed"]);
}