<?php
include "utils/connection.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About Us - Fresh Produce Market</title>
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
              <span class="block">About Us</span>
              <span class="block text-green-600">Our Story</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
              Learn about our journey, mission, and commitment to bringing fresh, local produce to your table.
            </p>
          </div>
        </div>
      </div>

      <!-- Our Story Section -->
      <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="lg:text-center mb-12">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Our Mission</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
              Connecting Farms to Families
            </p>
          </div>

          <div class="prose prose-lg mx-auto text-gray-500">
            <p>
              Founded in 2020, Fresh Produce Market began with a simple vision: to make fresh, local produce accessible to everyone. We believe that everyone deserves access to high-quality, nutritious fruits and vegetables at fair prices.
            </p>
            <p class="mt-4">
              Our platform connects local farmers directly with consumers, eliminating middlemen and ensuring both better prices for customers and fair compensation for farmers. We work with over 50 local farms and deliver to thousands of happy customers.
            </p>
          </div>
        </div>
      </div>

      <!-- Team Section -->
      <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="lg:text-center mb-12">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Our Team</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
              The People Behind Our Success
            </p>
          </div>

          <div class="mt-10">
            <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
              <!-- Team Member 1 -->
              <div class="relative">
                <div class="text-center">
                  <div class="w-full flex justify-center">
                    <img class="h-40 w-40 rounded-full" src="https://via.placeholder.com/160" alt="Team member">
                  </div>
                  <h3 class="mt-4 text-lg font-medium text-gray-900">John Doe</h3>
                  <p class="text-green-600">Founder & CEO</p>
                </div>
              </div>

              <!-- Team Member 2 -->
              <div class="relative">
                <div class="text-center">
                  <div class="w-full flex justify-center">
                    <img class="h-40 w-40 rounded-full" src="https://via.placeholder.com/160" alt="Team member">
                  </div>
                  <h3 class="mt-4 text-lg font-medium text-gray-900">Jane Smith</h3>
                  <p class="text-green-600">Operations Director</p>
                </div>
              </div>

              <!-- Team Member 3 -->
              <div class="relative">
                <div class="text-center">
                  <div class="w-full flex justify-center">
                    <img class="h-40 w-40 rounded-full" src="https://via.placeholder.com/160" alt="Team member">
                  </div>
                  <h3 class="mt-4 text-lg font-medium text-gray-900">Mike Johnson</h3>
                  <p class="text-green-600">Farm Relations Manager</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Values Section -->
      <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="lg:text-center">
            <h2 class="text-base text-green-600 font-semibold tracking-wide uppercase">Our Values</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
              What We Stand For
            </p>
          </div>

          <div class="mt-10">
            <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
              <!-- Value 1 -->
              <div class="relative">
                <div class="text-center">
                  <h3 class="mt-2 text-lg font-medium text-gray-900">Sustainability</h3>
                  <p class="mt-2 text-base text-gray-500">
                    Committed to environmentally friendly farming practices and reducing food waste
                  </p>
                </div>
              </div>

              <!-- Value 2 -->
              <div class="relative">
                <div class="text-center">
                  <h3 class="mt-2 text-lg font-medium text-gray-900">Community</h3>
                  <p class="mt-2 text-base text-gray-500">
                    Supporting local farmers and strengthening local food systems
                  </p>
                </div>
              </div>

              <!-- Value 3 -->
              <div class="relative">
                <div class="text-center">
                  <h3 class="mt-2 text-lg font-medium text-gray-900">Quality</h3>
                  <p class="mt-2 text-base text-gray-500">
                    Ensuring the highest standards for all our produce
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
