<?php
include "utils/connection.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Special Offers - My Website</title>
    <link rel="stylesheet" href="styling/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
  </head>
  <body>
    <main>
      <div>
          <?php include "components/navbar.php"; ?>
          <h1 class="text-3xl font-bold text-center my-6">Special Offers</h1>
          <?php include "utils/get_sale_products.php"; ?>
          <?php include "components/footer.php"; ?>
      </div>
    </main>
  </body>
</html>
