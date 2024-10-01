<?php
require_once 'dbConnection.php';  // Database connection file
require_once '../forms/Signup.php';  // Signup form

class dbHandler {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Function to handle signup form submission
    public function handleSignup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
            $fullname = $_POST['fullname'];
            $email = $_POST['email_address'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Validate inputs before inserting (optional)
            if ($this->validateInputs($fullname, $email, $username, $password)) {
                // Insert data into database
                $this->insertData($fullname, $email, $username, $password);
            } else {
                echo "Invalid input data!";
            }
        }
    }

    // Optional function to validate inputs
    private function validateInputs($fullname, $email, $username, $password) {
        // Add basic validation, like checking for empty fields
        if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
            return false;
        }
        return true;
    }

    // Function to insert data into the database
    public function insertData($fullname, $email, $username, $password) {
        try {
            // Prepare the SQL statement
            $stmt = $this->connection->prepare("INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)");

            // Bind parameters and execute
            $stmt->execute([$fullname, $email, $username, $password]);

            echo "User successfully registered!";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Initialize database connection
$Objdbconnect = new dbConnection();
$connection = $Objdbconnect->getConnection();

if ($connection instanceof PDO || $connection instanceof mysqli) {
    // Instantiate dbHandler with the connection and handle signup
    $ObjdbHandler = new dbHandler($connection);
    $ObjdbHandler->handleSignup();
} else {
    echo $connection; // Print error message if connection fails
}
