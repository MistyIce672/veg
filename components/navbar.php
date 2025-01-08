<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end h-16">
            <div class="flex space-x-8 items-center">
                <a href="index.php"
                   class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    HOME
                </a>

                <a href="vegetables.php"
                   class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    VEGETABLES
                </a>

                <a href="fruits.php"
                   class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    FRUITS
                </a>

                <a href="offers.php"
                   class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    OFFERS
                </a>

                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION["user_id"])) {
                    // If user is signed in, show their name as a link
                    echo '<a href="account.php" class="text-green-600 hover:text-green-800 font-medium">' .
                        htmlspecialchars($_SESSION["first_name"]) .
                        " " .
                        htmlspecialchars($_SESSION["last_name"]) .
                        '</a>
                        <a href="cart.php">
                            <button class="text-blue-600 hover:text-blue-900 bg-white hover:bg-gray-50 border border-blue-600 px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-shopping-cart"></i> Cart
                            </button>
                        </a>
                        <a href="utils/signout.php">
                            <button class="text-red-600 hover:text-red-900 bg-white hover:bg-gray-50 border border-red-600 px-4 py-2 rounded-md text-sm font-medium">
                                Sign Out
                            </button>
                        </a>';
                } else {
                    // If user is not signed in, show signin/register buttons
                    echo '<a href="signin.php">
                            <button class="text-green-600 hover:text-green-900 bg-white hover:bg-gray-50 border border-green-600 px-4 py-2 rounded-md text-sm font-medium">
                                Sign In
                            </button>
                          </a>

                          <a href="register.php">
                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Register
                            </button>
                          </a>';
                }
                ?>
            </div>
        </div>
    </div>
</nav>
