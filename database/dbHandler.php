<?php
require_once 'dbConnection.php';  // Database connection file
// require_once '../forms/Signup.php';

class dbHandler {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Function to validate inputs
    private function validateInputs($fullname, $email, $username, $password) {
        $errors = []; // Initialize an array to hold error messages
    
        // Check for empty fields
        if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
            return "All fields are required.";
        }
    
        // Validate fullname to ensure it contains only letters and spaces
        if (ctype_alpha(str_replace(" ", "", $fullname)) === FALSE) {
            $errors['nameLetters_err'] = "Invalid name format: Full name must contain letters and spaces only.";
        }
    
        // Validate email address format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email_format_err'] = 'Wrong email format.';
        } else {
            // Define valid domains
            $conf['valid_domains'] = ["strathmore.edu", "gmail.com", "yahoo.com", "mada.co.ke", "outlook.com", 
                                      "STRATHMORE.EDU", "GMAIL.COM", "YAHOO.COM", "MADA.CO.KE", "OUTLOOK.COM"];
    
            // Split email to check the domain
            $arr_email_address = explode("@", $email);
            $spot_dom = end($arr_email_address);
    
            // Check if the domain is valid
            if (!in_array($spot_dom, $conf['valid_domains'])) {
                $errors['mailDomain_err'] = "Invalid email address domain. Use only: " . implode(", ", $conf['valid_domains']);
            }
        }
    
        // Validate password strength
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSymbol = preg_match('/[\W]/', $password);
        $hasValidLength = strlen($password) >= 8;
    
        if (!$hasValidLength) {
            $errors['password_length_err'] = "Password must be at least 8 characters long.";
        } elseif (!$hasNumber || !$hasSymbol) {
            $errors['password_strength_err'] = "Password must contain at least one number and one special character.";
        }
    
        // If there are errors, return them
        if (!empty($errors)) {
            return implode("<br>", $errors); // Combine error messages for display
        }
    
        return null; // If everything is valid
    }

    // Function to handle the form submission
    public function handleSignup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
            $fullname = $_POST['fullname'];
            $email = $_POST['email_address'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Validate the inputs
            $validationError = $this->validateInputs($fullname, $email, $username, $password);

            if ($validationError) {
                // Return the error message
                return $validationError;
            } else {
                // Proceed with database insertion if validation passes
                if ($this->insertData($fullname, $email, $username, $password)) {
                    return "User successfully registered!";
                }
            }
        }
        return null; // No error
    }

    // Function to insert data into the database
    private function insertData($fullname, $email, $username, $password) {
        try {
            // Check if username or email already exists
            $sql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['username' => $username, 'email' => $email]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                return "Error: Username or email already exists!";
            }

            // Hash the password before saving it
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (fullname, email, username, password) VALUES (:fullname, :email, :username, :password)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['fullname' => $fullname, 'email' => $email, 'username' => $username, 'password' => $hashedPassword]);

            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
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

