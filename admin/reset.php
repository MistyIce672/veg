<?php
session_start();
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    // If not admin, redirect to login page
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password</title>
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Reset Password</h2>
                <p class="text-gray-600">Enter your username and new password</p>
            </div>

            <form action="reset_process.php" method="POST">
                <div class="mb-6">
                                    <label for="old_password" class="block text-gray-700 text-sm font-semibold mb-2">
                                        Current Password
                                    </label>
                                    <input
                                        type="password"
                                        id="old_password"
                                        name="old_password"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                        placeholder="Enter current password"
                                        required
                                    >
                                </div>

                <div class="mb-6">
                    <label for="new_password" class="block text-gray-700 text-sm font-semibold mb-2">
                        New Password
                    </label>
                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Enter new password"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block text-gray-700 text-sm font-semibold mb-2">
                        Confirm Password
                    </label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Confirm new password"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-300"
                >
                    Reset Password
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="login.php" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Back to login
                </a>
            </div>

            <?php
            if (isset($_GET["error"])) {
                $error = $_GET["error"];
                $message = "";
                switch ($error) {
                    case "1":
                        $message = "Passwords do not match";
                        break;
                    case "2":
                        $message = "Username not found";
                        break;
                    case "3":
                        $message = "System error occurred";
                        break;
                    case "4":
                        $message = "Current password is incorrect";
                        break;
                    default:
                        $message = "An error occurred";
                }
                echo "<div class='mt-4 text-center text-red-600'>$message</div>";
            }
            if (isset($_GET["success"])) {
                echo "<div class='mt-4 text-center text-green-600'>Password reset successful!</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
