<?php
// Include PreferenceController
require_once __DIR__ . "/../controllers/PreferencesControl.php";

// Create controller object
$controller = new PreferencesControl();

// Get request method
$method = $_SERVER["REQUEST_METHOD"];

// Route request based on method
if ($method === "GET") {
    // Get current user's preferences
    $controller->getPreferences();

} elseif ($method === "POST" || $method === "PUT") {
    // Save or update preferences
    $controller->savePreferences();

} elseif ($method === "DELETE") {
    // Delete current user's preferences
    $controller->deletePreferences();

} else {
    // Unsupported method
    jsonResponse(405, ["message" => "Method not allowed"]);
}