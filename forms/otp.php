<?php

class otp {
    public function opt_form($errorMessage = null) {
        ?>
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid d-flex justify-content-center">
                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="POST" class="d-flex" enctype="multipart/form-data">
                    <label for="otpcode">OTP Code</label>
                    <input class="form-control me-2" type="text" name="otpcode" placeholder="Enter OTP" required>
                    <button class="btn btn-outline-success" type="submit" name="otpverify">Verify</button>
                </form>
            </div>
        </nav>
        <?php
    }
}
