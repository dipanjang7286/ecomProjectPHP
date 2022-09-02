<?php
$servername = "localhost";
$username = "root";
$password = "himalayan";

// Create connection
$conn = new mysqli($servername, $username, $password, "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";
$GLOBALS["connection"] = $conn;

// class DatabaseService
// {

//     private $db_host = "localhost";
//     private $db_name = "project";
//     private $db_user = "root";
//     private $db_password = "himalayan";
//     public $conn;

//     public function getConnection()
//     {

//         $this->conn = null;

//         try {
//             $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
//         } catch (PDOException $exception) {
//             echo "Connection failed: " . $exception->getMessage();
//         }

//         return $this->conn;
//     }
// }
