<?php
require "load.php"; // Load required files

$ObjLayouts->heading();
$ObjNav->navBar();

// Initialize the database connection
$Objdbconnect = new dbConnection();
$connection = $Objdbconnect->getConnection();

// Create an instance of dbHandler
$dbHandler = new dbHandler($connection);

// Handle the signup form submission and get any error message
$errorMessage = null;
if ($connection instanceof PDO) {
    $errorMessage = $dbHandler->handleSignup(); // Get validation errors or success message
}

// Display the signup form (always display it here)
$ObjSignup = new Signup();
$ObjSignup->sign_up_form($errorMessage); // Pass error message to the form

$ObjLayouts->footer();
