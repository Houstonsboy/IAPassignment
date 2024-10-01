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
    public function getUser() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['getUser'])) {
            $username = $_POST['username'];
    
            // Fetch the user data based on the username
            $userData = $this->fetchData($username);
    
            if (!empty($userData)) {
                // Display the fetched user data (assuming one match, but you can handle multiple)
                echo "<table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>";
                foreach ($userData as $user) {
                    echo "<tr>
                        <td>{$user['username']}</td>
                        <td>{$user['email']}</td>
                    </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "No user found with that username.";
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
                echo "Error: Username or email already exists!";
                return false;
            
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
    public function fetchData($username)
{
    try {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
        return [];
    }
}

}

// Initialize database connection
// Initialize database connection
$Objdbconnect = new dbConnection();
$connection = $Objdbconnect->getConnection();

if ($connection instanceof PDO || $connection instanceof mysqli) {
    // Instantiate dbHandler with the connection and handle signup and search
    $ObjdbHandler = new dbHandler($connection);

    // Handle form actions
    if (isset($_POST['signup'])) {
        $ObjdbHandler->handleSignup();
    } elseif (isset($_POST['getUser'])) {
        $ObjdbHandler->getUser();
    }
} else {
    echo $connection; // Print error message if connection fails
}

