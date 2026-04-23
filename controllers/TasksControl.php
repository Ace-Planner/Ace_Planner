<?php
require_once __DIR__ . "/../config/AcePlannerDB.php";
require_once __DIR__ . "/../utils/response.php";
require_once __DIR__ . "/../utils/authentication.php";

class TasksControl {

    private PDO $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    // GET ALL TASKS
    public function getTasks(): void {

        // Require login
        $userId = requireAuth();

        // Fetch tasks for this user
        $stmt = $this->conn->prepare("SELECT * FROM Tasks WHERE userId = :id");
        $stmt->execute([":id" => $userId]);

        jsonResponse(200, ["tasks" => $stmt->fetchAll()]);
    }

    // CREATE TASK
    public function createTask(): void {

        $userId = requireAuth();
        $data = json_decode(file_get_contents("php://input"), true);

        $title = trim($data["title"]);

        if ($title === "") {
            jsonResponse(400, ["message" => "Title required"]);
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO Tasks (userId, title) VALUES (:userId, :title)"
        );

        $stmt->execute([
            ":userId" => $userId,
            ":title" => $title
        ]);

        jsonResponse(201, ["message" => "Task created"]);
    }
}