<?php
class globalVar {
    public function setVar($fullname, $email, $username, $password) {
        try{
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        }catch(Exception $e)
        {
            return false;
        }
        return true;
    }

    public function getVar() {
        // Check if the session variables exist, retrieve, and unset them
        $result = [];
        if(isset($_SESSION['fullname'])) {
            $result['fullname'] = $_SESSION['fullname'];
            unset($_SESSION['fullname']);
        }
        if(isset($_SESSION['email'])) {
            $result['email'] = $_SESSION['email'];
            unset($_SESSION['email']);
        }
        if(isset($_SESSION['username'])) {
            $result['username'] = $_SESSION['username'];
            unset($_SESSION['username']);
        }
        if(isset($_SESSION['password'])) {
            $result['password'] = $_SESSION['password'];
            unset($_SESSION['password']);
        }

        // Return the session data as an array
        return $result;
    }
}
?>
