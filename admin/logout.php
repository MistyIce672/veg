<?php
session_start();
// Destroy all session data
session_destroy();
// Clear admin login cookie if it exists
if (isset($_COOKIE["admin_login"])) {
    setcookie("admin_login", "", time() - 3600, "/");
}
// Redirect to login page
header("Location: login.php");
exit();
?>
