<?php
// Include database connection
require_once __DIR__ . "/../config/AcePlannerDB.php";

// Include response helper
require_once __DIR__ . "/../utils/response.php";

// Include auth helper
require_once __DIR__ . "/../utils/authentication.php";

// Controller for notification-related actions
class NotificationControl {

    // Store DB connection
    private PDO $conn;

    // Connect to DB when controller is created
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // =========================
    // GET ALL NOTIFICATIONS
    // =========================
    public function getNotifications(): void {

        // Require user to be logged in
        $userId = requireAuth();

        // Select notifications for this user, newest first
        $sql = "SELECT * FROM notifications
                WHERE userId = :userId
                ORDER BY createdAt DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":userId" => $userId
        ]);

        $notifications = $stmt->fetchAll();

        jsonResponse(200, ["notifications" => $notifications]);
    }

    // =========================
    // CREATE NOTIFICATION
    // =========================
    public function createNotification(): void {

        // Require login
        $userId = requireAuth();

        // Read incoming JSON data
        $data = json_decode(file_get_contents("php://input"), true);

        // Get input values
        $type = trim($data["type"] ?? "");
        $message = trim($data["message"] ?? "");
        $scheduledFor = $data["scheduledFor"] ?? null;

        // Message is required
        if ($message === "") {
            jsonResponse(400, ["message" => "Notification message is required"]);
        }

        // Insert notification into DB
        $sql = "INSERT INTO notifications
                (userId, type, message, scheduledFor)
                VALUES
                (:userId, :type, :message, :scheduledFor)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":userId" => $userId,
            ":type" => $type,
            ":message" => $message,
            ":scheduledFor" => $scheduledFor
        ]);

        jsonResponse(201, ["message" => "Notification created successfully"]);
    }

    // =========================
    // MARK NOTIFICATION AS READ
    // =========================
    public function markAsRead(): void {

        // Require login
        $userId = requireAuth();

        // Get notification id from URL
        $notificationId = $_GET["id"] ?? null;

        // Validate id
        if (!$notificationId) {
            jsonResponse(400, ["message" => "Notification id is required"]);
        }

        // Update notification to mark it as read
        $sql = "UPDATE notifications
                SET isRead = 1
                WHERE notificationId = :notificationId
                  AND userId = :userId";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":notificationId" => $notificationId,
            ":userId" => $userId
        ]);

        jsonResponse(200, ["message" => "Notification marked as read"]);
    }

    // =========================
    // DELETE NOTIFICATION
    // =========================
    public function deleteNotification(): void {

        // Require login
        $userId = requireAuth();

        // Get notification id
        $notificationId = $_GET["id"] ?? null;

        // Validate id
        if (!$notificationId) {
            jsonResponse(400, ["message" => "Notification id is required"]);
        }

        // Delete notification only if it belongs to current user
        $sql = "DELETE FROM Notifications
                WHERE notificationId = :notificationId
                  AND userId = :userId";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":notificationId" => $notificationId,
            ":userId" => $userId
        ]);

        jsonResponse(200, ["message" => "Notification deleted successfully"]);
    }
}