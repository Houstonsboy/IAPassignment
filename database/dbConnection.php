<?php
// Include the constants file
include 'constants.php';

class dbConnection {
    private $connection;

    public function __construct() {
        // Use constants directly
        $this->connection(DBTYPE, HOSTNAME, DBPORT, HOSTUSER, HOSTPASS, DBNAME);
    }

    public function connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name) {
        switch($db_type) {
            case 'PDO':
                if ($db_port !== null) {
                    $db_host .= ":" . $db_port;
                }
                try {
                    // Create the connection
                    $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                    // Set the PDO error mode to exception
                    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo "Connected successfully :-)"; 
                } catch(PDOException $e) {
                    return "Connection failed: " . $e->getMessage(); 
                }
                break;
            case 'MySQLi':
                if ($db_port !== null) {
                    $db_host .= ":" . $db_port;
                }
                // Create connection
                $this->connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
                // Check connection
                if ($this->connection->connect_error) {
                    return "Connection failed: " . $this->connection->connect_error; 
                } else {
                    echo "Connected successfully";
                }
                break;
        }
    }
}
