<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    
    <link rel="stylesheet" href="styling/style.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Create Account</h2>
                <p class="text-gray-600">Please fill in your information</p>
            </div>
            <?php
            session_start();
            if (isset($_SESSION["register_errors"])) {
                echo '<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">';
                foreach ($_SESSION["register_errors"] as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                }
                echo "</div>";
                unset($_SESSION["register_errors"]);
            }

            // Restore form data if there were errors
            $formData = $_SESSION["form_data"] ?? [];
            unset($_SESSION["form_data"]);
            ?>

            <form action="utils/register_process.php" method="POST">
                <div class="mb-6">
                    <label for="firstName" class="block text-gray-700 text-sm font-semibold mb-2">
                        First Name
                    </label>
                    <input
                        type="text"
                        id="firstName"
                        name="firstName"
                        value="<?php echo htmlspecialchars(
                            $formData["firstName"] ?? ""
                        ); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="John"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="lastName" class="block text-gray-700 text-sm font-semibold mb-2">
                        Last Name
                    </label>
                    <input
                        type="text"
                        id="lastName"
                        name="lastName"
                        value="<?php echo htmlspecialchars(
                            $formData["lastName"] ?? ""
                        ); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Doe"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars(
                            $formData["email"] ?? ""
                        ); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="your@email.com"
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
                        value="<?php echo htmlspecialchars(
                            $formData["password"] ?? ""
                        ); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="confirmPassword" class="block text-gray-700 text-sm font-semibold mb-2">
                        Confirm Password
                    </label>
                    <input
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        value="<?php echo htmlspecialchars(
                            $formData["confirmPassword"] ?? ""
                        ); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="mb-6 flex items-center">
                    <input
                        type="checkbox"
                        id="terms"
                        <?php echo isset($formData["terms"])
                            ? "checked"
                            : ""; ?>
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        required
                    >
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        I agree to the Terms and Conditions
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-300"
                >
                    Sign Up
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">
                        Sign in
                    </a>
                </p>
            </div>

        </div>
    </div>
</body>
</html>
