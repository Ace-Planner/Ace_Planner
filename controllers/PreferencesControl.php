<?php
// Include database connection
require_once __DIR__ . "/../config/AcePlannerDB.php";

// Include response helper
require_once __DIR__ . "/../utils/response.php";

// Include auth helper
require_once __DIR__ . "/../utils/authentication.php";

// Controller for user study/settings preferences
class PreferenceControl {

    // Store DB connection
    private PDO $conn;

    // Constructor connects to DB
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // =========================
    // GET USER PREFERENCES
    // =========================
    public function getPreferences(): void {

        // Require login and get logged-in user id
        $userId = requireAuth();

        // Select preferences for this user
        $sql = "SELECT * FROM preferences
                WHERE userId = :userId
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":userId" => $userId
        ]);

        $preferences = $stmt->fetch();

        jsonResponse(200, ["preferences" => $preferences]);
    }

    // =========================
    // SAVE OR UPDATE PREFERENCES
    // =========================
    public function savePreferences(): void {

        // Require login
        $userId = requireAuth();

        // Read request body JSON
        $data = json_decode(file_get_contents("php://input"), true);

        // Extract preference values, using defaults if none are provided
        $focusDuration = $data["focusDuration"] ?? 25;
        $shortBreak = $data["shortBreak"] ?? 5;
        $longBreak = $data["longBreak"] ?? 15;
        $defaultPriority = trim($data["defaultPriority"] ?? "medium");
        $startDayOfWeek = trim($data["startDayOfWeek"] ?? "Monday");
        $emailNotifications = $data["emailNotifications"] ?? true;
        $pushNotifications = $data["pushNotifications"] ?? true;
        $inAppNotifications = $data["inAppNotifications"] ?? true;

        // Check if preferences row already exists for this user
        $checkSql = "SELECT preferenceId FROM preferences WHERE userId = :userId";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->execute([
            ":userId" => $userId
        ]);

        $existing = $checkStmt->fetch();

        // If preferences already exist, update them
        if ($existing) {
            $sql = "UPDATE preferences
                    SET focusDuration = :focusDuration,
                        shortBreak = :shortBreak,
                        longBreak = :longBreak,
                        defaultPriority = :defaultPriority,
                        startDayOfWeek = :startDayOfWeek,
                        emailNotifications = :emailNotifications,
                        pushNotifications = :pushNotifications,
                        inAppNotifications = :inAppNotifications
                    WHERE userId = :userId";
        } else {
            // Otherwise insert a new preferences row
            $sql = "INSERT INTO preferences
                    (userId, focusDuration, shortBreak, longBreak, defaultPriority, startDayOfWeek,
                     emailNotifications, pushNotifications, inAppNotifications)
                    VALUES
                    (:userId, :focusDuration, :shortBreak, :longBreak, :defaultPriority, :startDayOfWeek,
                     :emailNotifications, :pushNotifications, :inAppNotifications)";
        }

        // Prepare and run query
        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":userId" => $userId,
            ":focusDuration" => $focusDuration,
            ":shortBreak" => $shortBreak,
            ":longBreak" => $longBreak,
            ":defaultPriority" => $defaultPriority,
            ":startDayOfWeek" => $startDayOfWeek,
            ":email_notifications" => $emailNotifications ? 1 : 0,
            ":push_notifications" => $pushNotifications ? 1 : 0,
            ":in_app_notifications" => $inAppNotifications ? 1 : 0
        ]);

        jsonResponse(200, ["message" => "Preferences saved successfully"]);
    }

    // =========================
    // DELETE USER PREFERENCES
    // =========================
    public function deletePreferences(): void {

        // Require login
        $userId = requireAuth();

        // Delete preferences row for current user
        $sql = "DELETE FROM preferences
                WHERE userId = :userId";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":userId" => $userId
        ]);

        jsonResponse(200, ["message" => "Preferences deleted successfully"]);
    }
}