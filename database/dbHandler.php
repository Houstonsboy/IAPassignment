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
            // Check if username or email already exists
            $sql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $email]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return "Error: Username or email already exists!";
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users(fullname,email, username, password) VALUES(:fullname, :email, :username, :password)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['fullname' => $fullname, 'email' => $email, 'username' => $username, 'password' => $hashedPassword]);
            echo "User successfully registered!";

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function fetchData()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Location: phpHandler.php');
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
