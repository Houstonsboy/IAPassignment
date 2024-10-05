<?php
require "load.php"; // Load required files
require_once "database/dbHandler.php";

$ObjLayouts->heading();
$ObjNav->navBar();
$connection = $Objdbconnect->getConnection();

// Create an instance of dbHandler
$dbHandler = new dbHandler($connection);

// Handle the signup form submission and get any error message
$errorMessage = null;
if ($connection instanceof PDO) {
    $errorMessage = $dbHandler->handleSignup(); // Get validation errors or success message
}
$Objotp-> opt_form();
// Check if the database connection is successful

// $ObjHeadings->main_banner();
// $ObjCont->main_content();
// $ObjCont->side_bar();
$ObjLayouts->footer();
