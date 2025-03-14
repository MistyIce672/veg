<?php
$servername = "db";
$username = "user";
$password = "1qaz2wsx";
$db_name = "veg";
<<<<<<< HEAD
$conn = new mysqli($servername, $username, $password, $db_name);
=======
$conn = new mysqli($servername, $username, $password, $db_name, 3306);
>>>>>>> main
if ($conn->connect_error) {
    die("connection failed" . $conn->connect_error);
}
echo "";
?>
