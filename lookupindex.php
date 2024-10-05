<?php
require "load.php"; // Load required files

$ObjLayouts->heading();
$ObjNav->navBar();
$Objlookup-> lookup_form();
// Check if the database connection is successful
$connection = $Objdbconnect->getConnection();

// Create an instance of dbHandler
$dbHandler = new dbHandler($connection);

// Handle the signup form submission and get any error message
$errorMessage = null;
if ($connection instanceof PDO) {
    $errorMessage = $dbHandler->getUser(); // Get validation errors or success message
}
// $ObjHeadings->main_banner();
// $ObjCont->main_content();
// $ObjCont->side_bar();
$ObjLayouts->footer();
