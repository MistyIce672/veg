<?php
include "utils/connection.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fresh Fruits</title>
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
          Fresh Fruits
        </h1>
        <p class="mt-3 text-gray-500">
          Browse our selection of fresh, locally sourced fruits
        </p>
      </div>

      <!-- Search and Filter Section -->

      <!-- Products Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        $categoryname = "Fruits";
        include "utils/get_products.php";
        ?>
      </div>
    </main>

    <?php include "components/footer.php"; ?>
  </body>
</html>
