<?php
include "utils/connection.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fresh Vegetables</title>
    <link rel="stylesheet" href="styling/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  </head>
  <body class="bg-gray-50">
    <?php include "components/navbar.php"; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Page Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
          Fresh Vegetables
        </h1>
        <p class="mt-3 text-gray-500">
          Browse our selection of fresh, locally sourced vegetables
        </p>
      </div>

      <!-- Search and Filter Section -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row gap-4">
          <!-- Search Bar -->
          <div class="flex-1">
            <form action="" method="GET" class="relative">
              <input
                type="text"
                name="search"
                placeholder="Search vegetables..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
              >
              <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </button>
            </form>
          </div>

          <!-- Filters -->
          <div class="flex gap-4">
            <select class="px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
              <option value="">Sort by</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="name">Name: A to Z</option>
            </select>

            <select class="px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
              <option value="">Filter by</option>
              <option value="organic">Organic</option>
              <option value="local">Local</option>
              <option value="seasonal">Seasonal</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        $categoryname = "Vegetables";
        include "utils/get_products.php";
        ?>
      </div>
    </main>

    <?php include "components/footer.php"; ?>
  </body>
</html>
