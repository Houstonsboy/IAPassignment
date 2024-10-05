<?php
session_start();

class Signup {
    public function sign_up_form($errorMessage = null) {
        ?>
        <style>
            body {
                height: 100vh; /* Full viewport height */
                background-image: url("assets/moonman.jpeg");
                background-size:cover;
                background-position:center;
            }
            .form-container {
                background-color: #333; /* Dark background for the form */
                padding: 30px;
                border-radius: 10px;
                color: white;
                box-shadow:2px 2px 80px grey            }
        </style>

        <div class="container h-100 d-flex justify-content-center align-items-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-container">
                        <h2 class="text-center mb-4">Sign Up</h2>

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
                            <button type="submit" name="signup" class="btn btn-primary w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
