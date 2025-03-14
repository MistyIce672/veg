-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 19, 2025 at 03:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `veg`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `email`, `password`, `first_name`, `last_name`, `created_at`, `updated_at`) VALUES
(2, 'travinahis14@gmail.com', '$2y$10$FP/WhFhWLZfn9PP3iFlzaOEPEcVlqHRdNSE0XTpFD8Yh9sKoPPGlC', 'Travin', 'Ahishayan', '2025-01-15 08:46:40', '2025-01-15 08:46:40');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(2, 'admin', '$2y$10$RycFIIRlPB8LkwvVOv3o1u1ZRVFqc6sA71sJwJej9egI5l.KjThr2', '2025-01-07 13:35:18');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(5, 'Vegetables'),
(6, 'Fruits');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`username`, `password`) VALUES
('gautham', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `account_id`, `full_name`, `address`, `city`, `postal_code`, `phone`, `total_amount`, `status`, `created_at`) VALUES
(3, 2, 'Travin Ahishayan', '202/38.1/1', 'Wattala', '11300', '0769069268', 250.00, 'pending', '2025-01-15 08:52:49'),
(4, 2, 'Travin Ahishayan', '202/38.1/1', 'Wattala', '11300', '0769069268', 50450.00, 'cancelled', '2025-01-19 13:40:11'),
(5, 2, 'Travin Ahishayan', '202/38.1/1', 'Wattala', '11300', '0769069268', 200.00, 'pending', '2025-01-19 13:42:48');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(6, 4, 7, 200, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `in_stock` tinyint(1) NOT NULL,
  `image` mediumblob DEFAULT NULL,
  `on_sale` tinyint(1) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sale_price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `in_stock`, `image`, `on_sale`, `category_id`, `sale_price`) VALUES
(5, 'Tomato', 'Fresh, Juicy, and Full of Flavor Tomatoes\r\nBrighten your meals with the vibrant taste of farm-fresh tomatoes. Perfectly ripe and bursting with natural sweetness, these versatile gems are ideal for salads, sauces, sandwiches, or cooking up your favorite recipes. Handpicked for quality, they bring a touch of garden freshness to your kitchen.', 300.00, 1, 0x75706c6f6164732f363738636637353930383731372e706e67, 1, 5, 250),
(6, 'Banana', 'Sweet, Creamy, and Naturally Energizing Bananas\r\nSavor the classic goodness of perfectly ripened bananas. Packed with natural sweetness and essential nutrients,\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 300.00, 1, 0x75706c6f6164732f363738636637323836636436342e6a706567, 0, 6, 0),
(7, 'Mango', 'Juicy, Sweet, and Irresistible Mangoes\r\nIndulge in the rich, tropical flavor of premium mangoes. Perfectly ripened for a burst of sweetness in every bite, these mangoes are a versatile treat—enjoy them fresh, blend into smoothies, or add a splash of sunshine to your recipes. Handpicked for quality, they’re nature’s delicious gift, delivered straight to your doorstep.\r\n\r\n', 250.00, 1, 0x75706c6f6164732f363738636663376233353264302e6a706567, 1, 6, 200),
(8, 'Beans', 'Crisp, Nutritious, and Versatile Beans\r\nEnjoy the garden-fresh goodness of premium beans. Packed with flavor and essential nutrients, these beans are perfect for stir-fries, salads, or steaming as a healthy side dish. Handpicked for quality and freshness, they’re a wholesome addition to every meal. Elevate your cooking with nature’s green delight!', 200.00, 1, 0x75706c6f6164732f363738636638363865333436332e6a706567, 0, 5, 0),
(9, 'Drumstick ', 'Fresh, Nutritious, and Flavorful Drumsticks\r\nAdd a healthy twist to your meals with premium drumsticks. Known for their distinct flavor and rich nutritional value, they’re perfect for curries, soups, or stews. Handpicked for freshness and quality, these drumsticks are a wholesome ingredient to elevate your culinary creations.', 250.00, 1, 0x75706c6f6164732f363738636661633334623534612e706e67, 0, 5, 0),
(10, 'Carrot', 'Crunchy, Sweet, and Packed with Goodness Carrots\r\nBrighten your meals with the natural sweetness and vibrant color of fresh carrots. Perfect for snacking, salads, soups, or roasting, these versatile veggies are a rich source of nutrients and flavor. Handpicked for quality and freshness, they’re a must-have for every healthy kitchen.', 150.00, 1, 0x75706c6f6164732f363738636663353630323665362e6a706567, 0, 5, 0),
(11, 'Potato', 'Versatile, Nutritious, and Perfectly Fresh Potatoes\r\nStock up on the hearty goodness of premium potatoes. Naturally rich in flavor and nutrients, these kitchen staples are perfect for mashing, roasting, frying, or adding to your favorite dishes. Handpicked for quality and freshness, they’re a must-have ingredient for countless delicious recipes.', 250.00, 1, 0x75706c6f6164732f363738643030343061353231382e6a7067, 1, 5, 175),
(12, 'Watermelon', 'Refreshing, Juicy, and Bursting with Sweetness Watermelons\r\nQuench your thirst with the hydrating sweetness of fresh watermelons. Packed with natural juices and vibrant flavor, they’re perfect for snacking, blending into drinks, or enjoying as a summertime treat. Handpicked for ripeness and quality, these watermelons bring a splash of freshness to your table.\r\n\r\n', 230.00, 1, 0x75706c6f6164732f363738643033326565646435642e6a706567, 0, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_details`
--

CREATE TABLE `shipping_details` (
  `id` int(11) NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_details`
--

INSERT INTO `shipping_details` (`id`, `account_id`, `full_name`, `address`, `city`, `postal_code`, `phone`, `created_at`) VALUES
(2, 2, 'Travin Ahishayan', '202/38.1/1', 'Wattala', '11300', '0769069268', '2025-01-15 08:52:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `shipping_details`
--
ALTER TABLE `shipping_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `shipping_details`
--
ALTER TABLE `shipping_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `shipping_details`
--
ALTER TABLE `shipping_details`
  ADD CONSTRAINT `shipping_details_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
