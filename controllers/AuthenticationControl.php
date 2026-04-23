<?php
require_once __DIR__ . "/../config/AcePlannerDB.php";
require_once __DIR__ . "/../utils/response.php";

// Start session
session_start();

class AuthenticationControl {

    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // REGISTER USER
    public function register(): void {

        // Get JSON request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Extract fields
        $firstName = trim($data["firstName"] ?? "");
        $userEmail = trim($data["userEmail"] ?? "");
        $password = $data["password"] ?? "";

        // Validate required fields
        if ($first_name === "" || $email === "" || $password === "") {
            jsonResponse(400, ["message" => "Missing required fields"]);
        }

        // Hash password before storing
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $sql = "INSERT INTO Users (firstName, userEmail, passwordHash)
                VALUES (:firstName, :userEmail, :passwordHash)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":firstName" => $firstName,
            ":userEmail" => $userEmail,
            ":passwordHash" => $passwordHash
        ]);

        jsonResponse(201, ["message" => "User registered"]);
    }

    // LOGIN USER
    public function login(): void {

        $data = json_decode(file_get_contents("php://input"), true);

        $userEmail = $data["userEmail"];
        $password = $data["password"];

        // Find user
        $stmt = $this->conn->prepare("SELECT * FROM Users WHERE userEmail = :email");
        $stmt->execute([":userEmail" => $userEmail]);
s
        $users = $stmt->fetch();

        // Verify password
        if (!$users || !password_verify($password, $users["passwordHash"])) {
            jsonResponse(401, ["message" => "Invalid credentials"]);
        }

        // Save user in session
        $_SESSION["userId"] = $users["id"];

        jsonResponse(200, ["message" => "Login successful"]);
    }

    // LOGOUT
    public function logout(): void {
        session_destroy();
        jsonResponse(200, ["message" => "Logged out"]);
    }
}