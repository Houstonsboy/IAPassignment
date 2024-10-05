<?php
require_once 'dbConnection.php';  // Database connection file
require_once 'phpMailer.php';     // PHPMailer class

class dbHandler {
    private $connection;
    private $random;
    private $Mailer; // Declare Mailer as a class property
    private $globalVar;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->Mailer = new mail(); // Initialize Mailer in the constructor
        $this->globalVar = new globalVar();
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
            $conf['valid_domains'] = ["strathmore.edu", "gmail.com", "yahoo.com", "mada.co.ke", "outlook.com"];

            // Split email to check the domain
            $arr_email_address = explode("@", $email);
            $spot_dom = end($arr_email_address);

            // Check if the domain is valid
            if (!in_array($spot_dom, $conf['valid_domains'])) {
                $errors['mailDomain_err'] = "Invalid email address domain. Use only: " . implode(", ", $conf['valid_domains']);
            }
        }

        // Check if the email already exists
        $emailQuery = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->connection->prepare($emailQuery);
        $stmt->execute(['email' => $email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists > 0) {
            $errors['mailExists_err'] = "Email already exists.";
        }

        // Check if the username already exists
        $usernameQuery = "SELECT COUNT(*) FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($usernameQuery);
        $stmt->execute(['username' => $username]);
        $usernameExists = $stmt->fetchColumn();

        if ($usernameExists > 0) {
            $errors['usernameExists_err'] = "Username already exists.";
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
                // Start output buffering to capture any output generated by PHPMailer.
                ob_start();
                $result = $this->Mailer->send_auth_email($email, $username); // Use the Mailer object

                if ($result === false) {
                    // If email sending fails, capture error and store in session.
                    ob_clean(); // Clean the buffer since an error occurred
                    return "Failed to send the OTP code"; // Handle the error accordingly
                } else {
                    // Capture and store the output from PHPMailer if necessary
                    ob_clean(); // Clean the buffer and discard output
                    $_SESSION['otpcode'] = $result['random'];         //store otpcode in session
 
                    // Set the session variables
                    $this->globalVar->setVar($fullname, $email, $username, $password);

                    // Redirect the user to the OTP page
                    header('Location: otpindex.php');
                    exit(); // Ensure script execution stops after redirect
                }
            }
        } 
        elseif($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['otpverify'])) {
            // Handle OTP verification
            $otpcode = $_POST['otpcode'];

            // Check if OTP matches
            if ($_SESSION['otpcode'] == $otpcode)  {
                // Retrieve stored user details
                $results = $this->globalVar->getVar();
                $fullname = $results['fullname'];
                $email = $results['email'];
                $username = $results['username'];
                $password = $results['password'];
                
                // Insert data into the database
                $insertResult = $this->insertData($fullname, $email, $username, $password);

                if ($insertResult === true) {
                    // If insertion is successful, display success message
                    header('Location: lookupindex.php');

                    
                } else {
                    $errors['error'] = "error message.";
                }
            } else {
                // If OTP doesn't match, return an error
                return "Invalid OTP. Please try again.";
            }
    }
        }
  

    // Function to insert data into the database
    private function insertData($fullname, $email, $username, $password) {
        try {
          // Check for existing username or email
          $sql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
          $stmt = $this->connection->prepare($sql);
          $stmt->execute(['username' => $username, 'email' => $email]);
          $count = $stmt->fetchColumn();
      
          if ($count > 0) {
            return "Error: Username or email already exists!";
          }
      
          // Hash the password
          $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);  // Adjust cost as needed
      
          // Insert data
          $sql = "INSERT INTO users (fullname, email, username, password) VALUES (:fullname, :email, :username, :password)";
          $stmt = $this->connection->prepare($sql);
          $stmt->execute(['fullname' => $fullname, 'email' => $email, 'username' => $username, 'password' => $hashedPassword]);
      
          return true;
        } catch (PDOException $e) {
          return "Error: " . $e->getMessage();
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
    
}
$Objdbconnect = new dbConnection();
$connection = $Objdbconnect->getConnection();

if ($connection instanceof PDO || $connection instanceof mysqli) {
    // Instantiate dbHandler with the connection and handle signup and search
    $ObjdbHandler = new dbHandler($connection);

    // Handle form actions
    if (isset($_POST['signup']) || isset($_POST['otpverify'])) {
        $ObjdbHandler->handleSignup();
    } elseif (isset($_POST['getUser'])) {
        $ObjdbHandler->getUser();
    }
    
} else {
    echo $connection; // Print error message if connection fails
}
