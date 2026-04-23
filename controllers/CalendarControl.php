<?php
// Include database connection
require_once __DIR__ . "/../config/AcePlannerDB.php";

// Include JSON response helper
require_once __DIR__ . "/../utils/response.php";

// Include login/auth helper
require_once __DIR__ . "/../utils/authentication.php";

// Controller for calendar events
class CalendarControl {

    // Store DB connection
    private PDO $conn;

    // Constructor connects to DB
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // =========================
    // GET ALL CALENDAR EVENTS
    // =========================
    public function getEvents(): void {

        // Require login
        $userId = requireAuth();

        // Select all calendar events belonging to the current user
        $sql = "SELECT * FROM calendarevents
                WHERE userId = :userId
                ORDER BY eventDate ASC, startTime ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":userId" => $userId
        ]);

        $events = $stmt->fetchAll();

        jsonResponse(200, ["events" => $events]);
    }

    // =========================
    // CREATE CALENDAR EVENT
    // =========================
    public function createEvent(): void {

        // Require login
        $userId = requireAuth();

        // Get JSON input
        $data = json_decode(file_get_contents("php://input"), true);

        // Extract and clean values
        $title = trim($data["title"] ?? "");
        $eventDate = $data["eventDate"] ?? null;
        $startTime = $data["startTime"] ?? null;
        $endTime = $data["endTime"] ?? null;
        $eventType = trim($data["eventType"] ?? "");
        $taskId = $data["taskId"] ?? null;

        // Validate required fields
        if ($title === "" || !$eventDate) {
            jsonResponse(400, ["message" => "Title and event date are required"]);
        }

        // Insert new event into CalendarEvents table
        $sql = "INSERT INTO calendarevents
                (userId, taskId, title, eventDate, startTime, endTime, eventType)
                VALUES
                (:userId, :taskId, :title, :eventDate, :startTime, :endTime, :eventType)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":userId" => $userId,
            ":taskId" => $taskId,
            ":title" => $title,
            ":eventDate" => $eventDate,
            ":startTime" => $startTime,
            ":endTime" => $endTime,
            ":eventType" => $eventType
        ]);

        jsonResponse(201, ["message" => "Calendar event created successfully"]);
    }

    // =========================
    // UPDATE CALENDAR EVENT
    // =========================
    public function updateEvent(): void {

        // Require login
        $userId = requireAuth();

        // Get event ID from URL
        $eventId = $_GET["id"] ?? null;

        // Validate presence of ID
        if (!$eventId) {
            jsonResponse(400, ["message" => "Event id is required"]);
        }

        // Read request body JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Extract and clean fields
        $title = trim($data["title"] ?? "");
        $eventDate = $data["eventDate"] ?? null;
        $startTime = $data["startTime"] ?? null;
        $endTime = $data["endTime"] ?? null;
        $eventType = trim($data["eventType"] ?? "");
        $taskId = $data["taskId"] ?? null;

        // Validate required fields
        if ($title === "" || !$eventDate) {
            jsonResponse(400, ["message" => "Title and event date are required"]);
        }

        // Update event only if it belongs to current user
        $sql = "UPDATE CalendarEvents
                SET taskId = :taskId,
                    title = :title,
                    eventDate = :eventDate,
                    startTime = :startTime,
                    endTime = :endTime,
                    eventType = :eventType
                WHERE eventId = :eventId
                  AND userId = :userId";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":taskId" => $taskId,
            ":title" => $title,
            ":eventDate" => $eventDate,
            ":startTime" => $startTime,
            ":endTime" => $endTime,
            ":eventType" => $eventType,
            ":eventId" => $eventId,
            ":userId" => $userId
        ]);

        jsonResponse(200, ["message" => "Calendar event updated successfully"]);
    }

    // =========================
    // DELETE CALENDAR EVENT
    // =========================
    public function deleteEvent(): void {

        // Require login
        $userId = requireAuth();

        // Get event id from query string
        $eventId = $_GET["id"] ?? null;

        // Validate
        if (!$eventId) {
            jsonResponse(400, ["message" => "Event id is required"]);
        }

        // Delete only if it belongs to current user
        $sql = "DELETE FROM calendarevents
                WHERE eventId = :eventId
                  AND userId = :userId";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":eventId" => $eventId,
            ":userId" => $userId
        ]);

        jsonResponse(200, ["message" => "Calendar event deleted successfully"]);
    }
}