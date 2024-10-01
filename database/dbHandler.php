<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';
require_once 'phpHandler.php';
require_once 'signup.php';
$conn = new PDO('mysql:host=localhost;dbname=iap', 'root', '#Gift@90210!');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new phpHandler($conn);
    $user->insertData($name, $email, $username, $password);
    echo "User successfully registered!";
} else {
    echo "Failed to register user!";
}