<?php
$servername = "db";
$username = "user";
$password = "1qaz2wsx";
$db_name = "veg";
$conn = new mysqli($servername, $username, $password, $db_name, 3306);
if ($conn->connect_error) {
    die("connection failed" . $conn->connect_error);
}
echo "";
?>
