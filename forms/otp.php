<?php

class otp {
    public function opt_form($errorMessage = null) {
        ?>
        <style>
            .optform {
                display: grid;
            grid-template-columns: 1fr 2fr; /* Two columns of equal width */
            gap: 10px; /* Space between the grid items */
            padding: 20px;
            }
            .form-container {
                background-color: #333; /* Dark background for the form */
                padding: 30px;
                border-radius: 10px;
                color: white;
            }
        </style>
            <h1 style="text-align:center">An otp code has been sent to your email.</h1>

        <div class="optform">
       <div>
       <img  style="width:100%; height:100vh; margin-bottom:20px;" src="assets/cb.jpeg" alt="">
       </div> 
       <div>
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
</div>
</div>

        <?php
    }
}
