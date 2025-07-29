<?php
$host = "localhost";
$user = "root";
$pass = ""; // default XAMPP password is empty
$dbname = "school_mgmt";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
