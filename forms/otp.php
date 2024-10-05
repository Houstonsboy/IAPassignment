<?php

class otp {
    public function opt_form($errorMessage = null) {
        ?>
        <h1 style="text-align:center">An otp code has been sent to your email.</h1>
        <nav class="navbar bg-body-tertiary">
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="d-flex flex-column align-items-center" enctype="multipart/form-data">
            <label for="otpcode" class="mb-2">OTP Code</label>
            <input class="form-control mb-2" type="text" name="otpcode" placeholder="Enter OTP" required>
            <button class="btn btn-outline-success" type="submit" name="otpverify">Verify</button>
        </form>
    </div>
</nav>

        <?php
    }
}
