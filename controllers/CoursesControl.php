<?php
// Include database connection
require_once __DIR__ . "/../config/AcePlannerDB.php";

// Include JSON response helper
require_once __DIR__ . "/../utils/response.php";

// Include authentication helper so only logged-in users can access routes
require_once __DIR__ . "/../utils/authentication.php";

// Controller for handling course-related actions
class CoursesControl {

    // Store database connection
    private PDO $conn;

    // Constructor connects to the database
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // =========================
    // GET ALL COURSES
    // =========================
    public function getCourses(): void {

        // Make sure user is logged in and get their user ID
        $userId = requireAuth();

        // SQL query to fetch all courses for the logged-in user
        $sql = "SELECT * FROM courses 
                WHERE userId = :userId
                ORDER BY courseName ASC";

        // Prepare the SQL statement
        $stmt = $this->conn->prepare($sql);

        // Execute with the logged-in user's ID
        $stmt->execute([
            ":userId" => $userId
        ]);

        // Fetch all matching courses
        $courses = $stmt->fetchAll();

        // Return courses as JSON
        jsonResponse(200, ["courses" => $courses]);
    }

    // =========================
    // CREATE A COURSE
    // =========================
    public function createCourse(): void {

        // Make sure user is logged in
        $userId = requireAuth();

        // Read JSON request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Get and clean incoming values
        $courseName = trim($data["courseName"] ?? "");
        $instructorName = trim($data["instructorName"] ?? "");
        $schedule = trim($data["schedule"] ?? "");

        // Validate required field
        if ($courseName === "") {
            jsonResponse(400, ["message" => "Course name is required"]);
        }

        // Insert new course into database
        $sql = "INSERT INTO courses (userId, courseName, instructorName, schedule)
                VALUES (:userId, :courseName, :instructorName, :schedule)";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":userId" => $userId,
            ":courseName" => $courseName,
            ":instructorName" => $instructorName,
            ":schedule" => $schedule
        ]);

        // Return success message
        jsonResponse(201, ["message" => "Course created successfully"]);
    }

    // =========================
    // UPDATE A COURSE
    // =========================
    public function updateCourse(): void {

        // Require login
        $userId = requireAuth();

        // Get course ID from query string, example: /courses?id=3
        $courseId = $_GET["id"] ?? null;

        // Validate course ID
        if (!$courseId) {
            jsonResponse(400, ["message" => "Course id is required"]);
        }

        // Read incoming JSON data
        $data = json_decode(file_get_contents("php://input"), true);

        // Clean input values
        $courseName = trim($data["courseName"] ?? "");
        $instructorName = trim($data["instructorName"] ?? "");
        $schedule = trim($data["schedule"] ?? "");

        // Make sure course name is not empty
        if ($courseName === "") {
            jsonResponse(400, ["message" => "Course name is required"]);
        }

        // Update the course only if it belongs to the logged-in user
        $sql = "UPDATE courses
                SET courseName = :courseName,
                    instructorName = :instructorName,
                    schedule = :schedule
                WHERE courseId = :courseId
                  AND userId = :userId";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":courseName" => $courseName,
            ":instructorName" => $instructorName,
            ":schedule" => $schedule,
            ":courseId" => $courseId,
            ":userId" => $userId
        ]);

        // Return success message
        jsonResponse(200, ["message" => "Course updated successfully"]);
    }

    // =========================
    // DELETE A COURSE
    // =========================
    public function deleteCourse(): void {

        // Require login
        $userId = requireAuth();

        // Get course ID from URL query string
        $courseId = $_GET["id"] ?? null;

        // Validate course ID
        if (!$courseId) {
            jsonResponse(400, ["message" => "Course id is required"]);
        }

        // Delete the course only if it belongs to the user
        $sql = "DELETE FROM courses
                WHERE courseId = :courseId
                  AND userId = :userId";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ":courseId" => $courseId,
            ":userId" => $userId
        ]);

        // Return success response
        jsonResponse(200, ["message" => "Course deleted successfully"]);
    }
}