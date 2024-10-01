<?php
include 'constants.php'; // Include the constants file

class dbConnection {
    private $connection;

    public function __construct() {
        // Initialize the connection in the constructor
        $this->connection = $this->connection(DBTYPE, HOSTNAME, DBPORT, HOSTUSER, HOSTPASS, DBNAME);
    }

    public function connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name) {
        switch($db_type) {
            case 'PDO':
                if ($db_port !== null) {
                    $db_host .= ":" . $db_port;
                }
                try {
                    // Create the connection
                    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                    // Set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo "Connected successfully with PDO :-)"; 
                    return $conn;
                } catch(PDOException $e) {
                    return "Connection failed: " . $e->getMessage(); 
                }
                break;
            case 'MySQLi':
                if ($db_port !== null) {
                    $db_host .= ":" . $db_port;
                }
                // Create connection
                $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
                // Check connection
                if ($conn->connect_error) {
                    return "Connection failed: " . $conn->connect_error; 
                } else {
                    echo "Connected successfully with MySQLi";
                    return $conn;
                }
                break;
            default:
                return "Unsupported database type.";
        }
    }

    // Method to get the connection object
    public function getConnection() {
        return $this->connection;
    }
}
