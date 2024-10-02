<?php
session_start();

class Signup {
    public function sign_up_form($errorMessage = null) {
        ?>
        <div class="row align-items-md-stretch">
            <div class="col-md-9">
                <div class="h-100 p-5 text-bg-dark rounded-3">
                    <h2>Sign Up</h2>
                    
                    <!-- Display error message if validation fails -->
                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Fullname:</label>
                            <input type="text" name="fullname" class="form-control form-control-lg" maxlength="50" id="fullname" placeholder="Enter your name" value="<?php echo $_POST['fullname'] ?? ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email_address" class="form-label">Email Address:</label>
                            <input type="email" name="email_address" class="form-control form-control-lg" maxlength="50" id="email_address" placeholder="Enter your email address" value="<?php echo $_POST['email_address'] ?? ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" name="username" class="form-control form-control-lg" maxlength="50" id="username" placeholder="Enter your username" value="<?php echo $_POST['username'] ?? ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" name="password" class="form-control form-control-lg" maxlength="50" id="password" placeholder="Enter your password">
                        </div>
                        <button type="submit" name="signup" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    
}

// Class to handle form validation and database interaction
class dbHandler {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Function to validate inputs
    private function validateInputs($fullname, $email, $username, $password) {
        if (empty($fullname) || empty($email) || empty($username) || empty($password)) {
            return "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }

        // Validate password strength
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSymbol = preg_match('/[\W]/', $password);
        $hasValidLength = strlen($password) >= 8;

        if (!$hasValidLength) {
            return "Password must be at least 8 characters long.";
        } elseif (!$hasNumber || !$hasSymbol) {
            return "Password must contain at least one number and one special character.";
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


// // Initialize the database connection
// $Objdbconnect = new dbConnection();
// $connection = $Objdbconnect->getConnection();

// // If the connection is successful, handle the signup form submission
// if ($connection instanceof PDO) {
//     $dbHandler = new dbHandler($connection);
//     $dbHandler->handleSignup();
// } else {
//     echo "Database connection error!";
// }
