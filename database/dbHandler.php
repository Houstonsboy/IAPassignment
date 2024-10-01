<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';
require_once 'signup.php';
class dbHandler{
    $connection = $Objdbconnect->getConnection(); 

// Check if it's a valid connection object or error message
if ($connection instanceof PDO || $connection instanceof mysqli) {
    echo "Connection established successfully!";
} else {
    echo $connection; // This will print the error message if the connection failed
}

$ObjdbEndpoint=new dbEndpoints($connection);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $ObjdbEndpoint->insertData($name, $email, $username, $password);
    echo "User successfully registered!";
} else {
    echo "Failed to register user!";
}
}
