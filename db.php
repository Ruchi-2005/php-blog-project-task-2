<?php
$servername = "localhost";
$username = "root"; // default username
$password = ""; // default password is empty
$dbname = "blog";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
