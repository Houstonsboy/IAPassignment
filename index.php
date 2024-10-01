<?php
require "load.php"; // Load required files

$ObjLayouts->heading();
$ObjNav->navBar();
$ObjSignup->sign_up_form();

// Check if the database connection is successful
$connection = $Objdbconnect->getConnection(); 

// Check if it's a valid connection object or error message
if ($connection instanceof PDO || $connection instanceof mysqli) {
    echo "Connection established successfully!";
} else {
    echo $connection; // This will print the error message if the connection failed
}

// $ObjHeadings->main_banner();
// $ObjCont->main_content();
// $ObjCont->side_bar();
$ObjLayouts->footer();
