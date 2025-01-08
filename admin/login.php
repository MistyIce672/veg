<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="../styling/style.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Admin Portal</h2>
                <p class="text-gray-600">Please login to access admin dashboard</p>
            </div>

            <form action="admin_login_process.php" method="POST">
                <div class="mb-6">
                    <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">
                        Username
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Enter admin username"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Enter admin password"
                        required
                    >
                </div>

                <div class="mb-6">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-600">
                            Keep me signed in
                        </label>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-300"
                >
                    Login to Dashboard
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="forgot_password.php" class="text-sm text-purple-600 hover:text-purple-800">
                    Forgot Password?
                </a>
            </div>

            <div class="mt-4 text-center">
                <a href="index.php" class="text-sm text-gray-600 hover:text-gray-800">
                    ← Back to main site
                </a>
            </div>

            <?php // Display error message if any
// Display error message if any
// Display error message if any
            // Display error message if any
            if (isset($_GET["error"])) {
                echo '<div class="mt-4 text-center text-red-600">
                    Invalid username or password
                </div>';
            } ?>
        </div>
    </div>
</body>
</html>
