<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 
$host = 'localhost'; // Update this if your hosting provider specifies a different host (e.g., mysql.rahul.millioncoders.net)
$user = 'uzrprp3rmtdfr';
$password = '#[qI(M3@k1bz';
$dbname = 'dbdmvgvuqzi6p0';
 
$conn = new mysqli($host, $user, $password, $dbname);
 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
