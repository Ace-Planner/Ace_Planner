<?php
//This handles the connecting to the MySQL
class Database   {
    
    //Database settings
    private string $host = "localhost";
    private string $db_name = "ace_planner";
    private string $username = "root";
    private string $password = "";

    //PDO connection
    public ?PDO $conn = null;

    //The method to establishing connection
    public function connect(): PDO {
        $this->conn = null;

        try {
            //PDO conneciton string
            $this->conn = new PDO(
                "mysql:host={$this->host}; dbname={$this->db_name}; charset=utf8mb4",
                $this->username,
                $this->password
            );

            // Throw exception errors
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION):

            //Returns the results as associative arrays
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        }
        catch (PDOException $e)  {
            //If the connection fails, stops the execution and shows the error
            http_response_code(500);
            echo json_encode([
                "message" => "Database connection failed",
                "error" => $e->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}