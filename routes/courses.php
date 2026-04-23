<?php
// Include CourseController
require_once __DIR__ . "/../controllers/CoursesControl.php";

// Create controller object
$controller = new CoursesControl();

// Get HTTP method (GET, POST, PUT, DELETE)
$method = $_SERVER["REQUEST_METHOD"];

// Route request based on method
if ($method === "GET") {
    // Return all courses for logged-in user
    $controller->getCourses();

} elseif ($method === "POST") {
    // Create a new course
    $controller->createCourse();

} elseif ($method === "PUT") {
    // Update an existing course
    $controller->updateCourse();

} elseif ($method === "DELETE") {
    // Delete a course
    $controller->deleteCourse();

} else {
    // If method not supported, return error
    jsonResponse(405, ["message" => "Method not allowed"]);
}