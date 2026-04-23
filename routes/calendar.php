<?php
// Include CalendarController
require_once __DIR__ . "/../controllers/CalendarControl.php";

// Create controller object
$controller = new CalendarControl();

// Get current HTTP method
$method = $_SERVER["REQUEST_METHOD"];

// Route based on request method
if ($method === "GET") {
    // Return all calendar events
    $controller->getEvents();

} elseif ($method === "POST") {
    // Create a new event
    $controller->createEvent();

} elseif ($method === "PUT") {
    // Update an existing event
    $controller->updateEvent();

} elseif ($method === "DELETE") {
    // Delete an event
    $controller->deleteEvent();

} else {
    // Return method-not-allowed if unsupported
    jsonResponse(405, ["message" => "Method not allowed"]);
}