<?php

// require "includes/constants.php";
require "database/dbConnection.php";

// Class Auto Load 

function classAutoLoad($classname){

    $directories = [ "layouts","database", "forms", "navigation"];

    foreach($directories AS $dir){
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $classname . ".php";
        if(file_exists($filename) AND is_readable($filename)){
            require_once $filename;
        }
    }
}

spl_autoload_register('classAutoLoad');

// Create instances of all classes
    $ObjLayouts = new layouts();
    $ObjNav = new NavBar();
    $ObjSignup= new signup();
    // $ObjCont = new contents();
    // $ObjForm = new user_forms();
    // $conn = new dbConnection(DBTYPE, HOSTNAME, DBPORT, HOSTUSER, HOSTPASS, DBNAME);


// Create process instances

    // $ObjAuth = new auth();
    // $ObjAuth->signup($conn);
