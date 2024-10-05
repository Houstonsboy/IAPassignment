<?php

class otp {
    public function opt_form() {
        ?>
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid d-flex justify-content-center">
                <form action=" " method="POST" class="d-flex" role="search">
                    <label for="otpcode">otp code</label>
                    <input class="form-control me-2" type="search" name="otpcode" placeholder="OTP code" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit" name="otpverify">Done</button>
                </form>
            </div>
        </nav>
        <?php
    }
}
