<nav class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative flex justify-between h-16">
            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button type="button" onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Desktop menu -->
            <div class="hidden md:flex md:space-x-4 md:items-center md:justify-end md:flex-1">
                <a href="index.php" class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    HOME
                </a>

                <a href="vegetables.php" class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    VEGETABLES
                </a>

                <a href="fruits.php" class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    FRUITS
                </a>

                <a href="offers.php" class="text-gray-600 hover:text-green-600 hover:border-green-600 px-3 py-2 text-sm font-medium">
                    OFFERS
                </a>

                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION["user_id"])) {
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

        <!-- Mobile menu, show/hide based on menu state -->
        <div id="mobileMenu" class="hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="index.php" class="block text-gray-600 hover:text-green-600 px-3 py-2 text-base font-medium">HOME</a>
                <a href="vegetables.php" class="block text-gray-600 hover:text-green-600 px-3 py-2 text-base font-medium">VEGETABLES</a>
                <a href="fruits.php" class="block text-gray-600 hover:text-green-600 px-3 py-2 text-base font-medium">FRUITS</a>
                <a href="offers.php" class="block text-gray-600 hover:text-green-600 px-3 py-2 text-base font-medium">OFFERS</a>
                <?php if (isset($_SESSION["user_id"])) {
                    echo '<a href="account.php" class="block text-green-600 hover:text-green-800 px-3 py-2 text-base font-medium">' .
                        htmlspecialchars($_SESSION["first_name"]) .
                        " " .
                        htmlspecialchars($_SESSION["last_name"]) .
                        '</a>
                        <a href="cart.php" class="block text-blue-600 hover:text-blue-900 px-3 py-2 text-base font-medium">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                        <a href="utils/signout.php" class="block text-red-600 hover:text-red-900 px-3 py-2 text-base font-medium">
                            Sign Out
                        </a>';
                } else {
                    echo '<a href="signin.php" class="block text-green-600 hover:text-green-900 px-3 py-2 text-base font-medium">Sign In</a>
                          <a href="register.php" class="block text-green-600 hover:text-green-900 px-3 py-2 text-base font-medium">Register</a>';
                } ?>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.classList.toggle('hidden');
}
</script>
