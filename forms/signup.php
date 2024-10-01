<?php
class Signup {
    public function sign_up_form() {
        ?>
        <div class="row align-items-md-stretch">
            <div class="col-md-9">
                <div class="h-100 p-5 text-bg-dark rounded-3">
                    <h2>Sign Up</h2>
                    <!-- Form submission points to dbHandler.php -->
                    <form action="dbHandler.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Fullname:</label>
                            <input type="text" name="fullname" class="form-control form-control-lg" maxlength="50" id="fullname" placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="email_address" class="form-label">Email Address:</label>
                            <input type="email" name="email_address" class="form-control form-control-lg" maxlength="50" id="email_address" placeholder="Enter your email address">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" name="username" class="form-control form-control-lg" maxlength="50" id="username" placeholder="Enter your username">
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
