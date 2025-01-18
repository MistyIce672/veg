<?php
include "utils/connection.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fresh Produce Market</title>
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styling/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  </head>
  <body class="bg-gray-50">
    <?php include "components/navbar.php"; ?>

    <main>
      <!-- Hero Section -->
      <div class="relative bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
          <div class="text-center">
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
              <span class="block">Fresh Produce</span>
              <span class="block text-green-600">Delivered to Your Door</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
              Your one-stop online shop for fresh, local, and organic produce. From farm to table, we bring nature's best right to your doorstep.
            </p>
            <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
              <div class="rounded-md shadow">
                <a href="vegetables.php" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                  Shop Now
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="lg:text-center">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Why Choose Us</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
              Fresh From Local Farms
            </p>
          </div>

          <div class="mt-10">
            <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
              <!-- Feature 1 -->
              <div class="relative">
                <div class="text-center">
                    <div class="w-full flex justify-center">
                  <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white left-1/2">
                    <!-- Icon -->
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                  </div>
                  </div>
                  <h3 class="mt-2 text-lg font-medium text-gray-900">100% Fresh</h3>
                  <p class="mt-2 text-base text-gray-500">
                    All our produce is harvested daily and delivered within 24 hours
                  </p>
                </div>
              </div>

              <!-- Feature 2 -->
              <div class="relative">
                <div class="text-center">
                    <div class="w-full flex justify-center">
                  <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white left-1/2">
                    <!-- Icon -->
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                    </div>
                  <h3 class="mt-2 text-lg font-medium text-gray-900">Fast Delivery</h3>
                  <p class="mt-2 text-base text-gray-500">
                    Same-day delivery available for orders placed before 2 PM
                  </p>
                </div>
              </div>

              <!-- Feature 3 -->
              <div class="relative">
                <div class="text-center">
                    <div class="w-full flex justify-center">
                  <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white left-1/2">
                    <!-- Icon -->
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                    </svg>
                  </div>
                    </div>
                  <h3 class="mt-2 text-lg font-medium text-gray-900">Best Prices</h3>
                  <p class="mt-2 text-base text-gray-500">
                    Competitive prices direct from local farmers
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include "components/footer.php"; ?>
    </main>
  </body>
</html>
