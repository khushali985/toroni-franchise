-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2026 at 06:59 AM
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
-- Database: `toroni_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `status`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'gwansik', 'mss25092005@gmail.com', '$2y$12$LmmE/KJNS0ML2N2fGfRM2uMzwGquJRN2iA6C.4wrrqhepNH7eopKC', 'admin', 'active', NULL, NULL, NULL, '2026-03-11 01:08:48'),
(2, 'tuktuk', 'gaganoffice443@gmail.com', '$2y$12$l9XVyIwwGN8bwVWpZBGRf.pYiPWV3RSriUO2XMceixsIh9C.bZP8m', 'admin', 'active', NULL, NULL, NULL, '2026-03-11 03:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `franchises`
--

CREATE TABLE `franchises` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(150) NOT NULL,
  `owner_phone` varchar(15) DEFAULT NULL,
  `owner_email` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `location` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `franchises`
--

INSERT INTO `franchises` (`id`, `owner_name`, `owner_phone`, `owner_email`, `image`, `location`, `created_at`, `updated_at`) VALUES
(1, 'Khushali Patel', '7843973493', 'khushali@gmail.com', 'images/franchise/1773039691_f4.png', 'Vadodara', '2026-02-24 02:37:19', '2026-03-09 01:31:31'),
(2, 'Simran Mishra', '6356972456', 'simran@gmail.com', 'images/franchise/1773039677_f3.png', 'Navsari', '2026-02-24 02:38:10', '2026-03-09 01:31:17'),
(3, 'Vaibhavi Panchal', '9876450987', 'vaibhavi@gmail.com', 'images/franchise/1773039664_f2.png', 'Dahod', '2026-02-24 02:39:14', '2026-03-09 01:31:04'),
(4, 'Shivani Mishra', '9876450987', 'mpvaibhavipanchal001080@gmail.com', 'images/franchise/1773039648_f1.png', 'Betiyah', '2026-03-06 04:15:16', '2026-03-09 01:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `franchise_partners`
--

CREATE TABLE `franchise_partners` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `city` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `franchise_partners`
--

INSERT INTO `franchise_partners` (`id`, `name`, `email`, `phone`, `city`, `created_at`, `updated_at`) VALUES
(2, 'Khushali Patel', 'khushali@gmail.com', '7954637282', 'Vadodara', '2026-02-24 02:31:14', '2026-02-24 02:33:32'),
(3, 'Simran Mishra', 'simran@gmail.com', '6357658263', 'Navsari', '2026-02-24 02:33:05', '2026-02-24 02:33:05'),
(4, 'Vaibhavi Panchal', 'vaibhavi@gmail.com', '9832862765', 'Dahod', '2026-02-24 02:34:49', '2026-02-24 02:34:49'),
(5, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '09978222004', 'Vadodara', '2026-03-06 03:48:49', '2026-03-06 03:48:49'),
(6, 'gagan', 'naipaata@gmail.com', '9871123465', 'Vapi', '2026-03-09 04:43:16', '2026-03-09 04:43:16'),
(7, 'Vaibhavi Panchal', 'mpvaibhavipanchal001080@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:17:18', '2026-03-12 23:17:18'),
(8, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:29:36', '2026-03-12 23:29:36'),
(9, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:37:27', '2026-03-12 23:37:27'),
(10, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:41:25', '2026-03-12 23:41:25'),
(11, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:42:09', '2026-03-12 23:42:09'),
(12, 'Vaibhavi Panchal', 'DouglasThurston@theCloneStore660.onmicrosoft.com', '9978222004', 'Vadodara', '2026-03-12 23:42:58', '2026-03-12 23:42:58'),
(13, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:43:41', '2026-03-12 23:43:41'),
(14, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:44:10', '2026-03-12 23:44:10'),
(15, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:44:36', '2026-03-12 23:44:36'),
(16, 'VAIBHAVI RAJESHKUMAR PANCHAL', 'vaibhavi2226@gmail.com', '9978222004', 'Vadodara', '2026-03-12 23:45:25', '2026-03-12 23:45:25'),
(22, 'drty', 'dfgh@gmail.com', '1234567890', 'asedrtfgy', '2026-03-16 23:14:18', '2026-03-16 23:14:18');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `franchise_id` int(11) DEFAULT NULL,
  `dish_name` varchar(150) NOT NULL,
  `ingredients` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `category` varchar(150) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `franchise_id`, `dish_name`, `ingredients`, `price`, `category`, `image`, `created_at`, `updated_at`) VALUES
(18, 1, 'Margherita Pizza', 'Tomato sauce, mozzarella, fresh basil, olive oil', 480.00, 'Pizza', 'menu_images/FVrprmOoXmQGuouCrM23gsThrPYtyH0I5eH8mcad.jpg', '2026-03-09 03:13:27', '2026-03-13 03:19:36'),
(19, 2, 'Diavola Pizza', 'Tomato sauce, mozzarella, spicy pepper, basil', 650.00, 'Pizza', 'menu_images/HZUGJJdEltvP1BevsuGimZfL4ShBdC1CtfLrtZth.jpg', '2026-03-09 03:14:41', '2026-03-13 03:19:36'),
(20, 3, 'Quattro Formaggi Pizza', 'Mozzarella, gorgonzola, parmesan, fontina cheese', 690.00, 'Pizza', 'menu_images/0OUxT2ydk9MXRd3XjMIumpdu0F1VbbpV7JtRE9Ik.jpg', '2026-03-09 03:16:13', '2026-03-13 03:19:36'),
(21, 4, 'Verdure Pizza', 'Tomato sauce, zucchini, olives, bell peppers, mozzarella', 620.00, 'Pizza', 'menu_images/XhqC6q9J8jyiDJ3asoS1jwOUjcXNZCilzIT2Dvy0.webp', '2026-03-09 03:17:19', '2026-03-13 03:19:36'),
(22, 1, 'Spaghetti Aglio e Olio', 'Spaghetti, garlic, olive oil, chili flakes', 480.00, 'Pasta', 'menu_images/xQid9ShM2m2wUwZ69bkOMzwOYugD36PzkoeGeNHI.jpg', '2026-03-09 03:19:16', '2026-03-13 05:54:15'),
(23, 2, 'Penne Arrabbiata', 'Penne pasta, spicy tomato sauce, herbs', 500.00, 'Pasta', 'menu_images/gOHol2neECRZXOVEd1n1jnJECeIJQSdYnPZiuuMP.jpg', '2026-03-09 03:38:00', '2026-03-13 05:54:15'),
(24, 3, 'Orecchiette Verdure', 'Orecchiette pasta, seasonal vegetables, olive oil', 520.00, 'Pasta', 'menu_images/PdZD1qcXiDjkuMP6ZrtSa32gnghcfcLJCuMFVfYv.jpg', '2026-03-09 03:39:08', '2026-03-13 05:54:15'),
(25, 4, 'Creamy Alfredo Pasta', 'Fettuccine, cream sauce, parmesan cheese', 560.00, 'Pasta', 'menu_images/hSCqmk8cKoKYMIa8hsLlBj4bB9b6SNMMpUkiM8os.jpg', '2026-03-09 03:39:44', '2026-03-13 05:54:15'),
(26, 1, 'Classic Tiramisu', 'Mascarpone cheese, coffee, cocoa, ladyfingers', 420.00, 'Dessert', 'menu_images/TPTT8QjFbnd3rU6Nu9b45INvIMIMYiPI8wTf7lCJ.webp', '2026-03-09 03:42:49', '2026-03-14 00:04:54'),
(27, 2, 'Sicilian Cannoli', 'Crispy pastry shell, ricotta cream, chocolate chips', 390.00, 'Dessert', 'menu_images/8uCzC8Qd6Ri0LwT6NbrSqKh4gw79YNtowdCmjwoC.jpg', '2026-03-09 03:43:54', '2026-03-14 00:04:54'),
(28, 3, 'Vanilla Panna Cotta', 'Cream dessert with strawberry sauce', 360.00, 'Dessert', 'menu_images/9or44pnanXAwmWlqDNhMh1gcSxAPrJ8aTNdvHXWa.jpg', '2026-03-09 03:44:44', '2026-03-14 00:04:54'),
(29, 4, 'Chocolate Lava Cake', 'Warm chocolate cake with molten center', 420.00, 'Dessert', 'menu_images/e64g8ETsRXYaJk8XYLODhFuw3YvoRsmGIODHw0X4.jpg', '2026-03-09 03:46:24', '2026-03-14 00:04:54'),
(32, 1, 'Garlic bread', 'jfjinf,idnifj,rvv', 700.00, 'Pizza', 'menu_images/i8LZzZfpoLnQHlq3zSCbKyxruSHgdW8MBNs0LyW0.jpg', '2026-03-11 01:45:05', '2026-03-13 03:19:36'),
(33, 1, 'Chesse Garlic Bread', 'ryvv,yvdygyd, hvrg, rgefvv', 503.00, 'Pizza', 'menu_images/H5zilzvRPGqyFA12btxbHwDeR1KWcLu5hiTkoRHa.jpg', '2026-03-11 01:47:12', '2026-03-13 03:19:36'),
(34, 1, 'Chesse Garlic Bread', 'ryvv,yvdygyd, hvrg, rgefvv', 503.00, 'Pizza', 'menu_images/fQCgA6vge4oalfPwvCJDcMGYYymCe7L6Wuk6081z.jpg', '2026-03-11 01:47:13', '2026-03-13 03:19:36'),
(35, 1, 'hakka noodles', 'dcrfvr, efbv, gfresx', 455.00, 'Pizza', 'menu_images/3QFJ7H4ZJpVJGTY4Z3hIyFHSrUzH13iacMJ9lG9q.jpg', '2026-03-11 01:48:23', '2026-03-13 03:19:36'),
(36, 1, 'burata', 'v wbhd, fgywb, hewbu', 432.00, 'Pizza', 'menu_images/K5C4US4K9EmdC32MEeYaazoNzgGhLrc0CvWrnr0G.jpg', '2026-03-11 01:49:30', '2026-03-13 03:19:36'),
(37, 1, 'tyyd', 'svrv, uhvb, jnun', 689.00, 'Pizza', 'menu_images/mV5MnhB6lRSbQI3hV3Uu27kVBYqVzRNHvfp92iIA.jpg', '2026-03-11 01:50:03', '2026-03-13 03:19:36'),
(38, 1, 'hhhaaa', 'thu , thu , ku, ku', 789.00, 'Pizza', 'menu_images/vIiWXZthKuYYQ2FpAQQrtB7Ag7mjA5VZcHEw6ndv.jpg', '2026-03-11 01:50:37', '2026-03-13 03:19:36'),
(39, 1, 'davsD', 'CXVBGRTEAGFSD', 4543.00, 'Pizza', 'menu_images/aMS7P5W6MeIGf4374jfymVbM8fjV1mEiI2wN5Za1.jpg', '2026-03-11 03:48:44', '2026-03-13 03:19:36'),
(40, 1, 'DFBGADEWGGTRBSD', 'DFVADAEW', 453.00, 'Pizza', 'menu_images/JjEehIdcYbuit2FkkYBJkipn60H4xb8XTMRkmo4o.jpg', '2026-03-11 03:49:15', '2026-03-13 03:19:36'),
(41, 1, 'gw', 'rttn', 3.00, 'Pizza', 'menu_images/jJhMTeZuugj94Ovfiw5HtwuceSteqJcaxJ28xSmB.jpg', '2026-03-11 03:49:38', '2026-03-13 03:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_18_085619_create_franchises_table', 1),
(5, '2026_02_18_085620_create_reservations_table', 1),
(6, '2026_02_18_085620_create_restaurant_tables_table', 1),
(7, '2026_02_23_112534_make_ingredients_nullable', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `franchise_id` int(11) DEFAULT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `email` varchar(150) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','completed','cancelled','preparing') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `franchise_id`, `full_name`, `phone`, `address`, `items`, `email`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'dcxzcc', '1234567890', 'dfdfcv', '\"{\\\"noodles\\\":{\\\"name\\\":\\\"noodles\\\",\\\"price\\\":50,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/127.0.0.1:8000\\/storage\\/menu_images\\/Olne6pgXwsMnQnPCHJXBYdv6ev6ajiNGmuNfuGhS.jpg\\\"}}\"', 'sdfd@gmail.com', 50.00, 'completed', '2026-02-25 06:06:13', '2026-02-27 00:26:44'),
(2, 2, 'ffgfv', '1234567890', 'dfbcvgngvg', '\"{\\\"rgujdthrgsefawd\\\":{\\\"name\\\":\\\"rgujdthrgsefawd\\\",\\\"price\\\":99,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/127.0.0.1:8000\\/storage\\/menu_images\\/mK5AUaX3AQyIZQit938rMp5SWso10D9iUL0GYdgd.png\\\"},\\\"fbfeywrshgef\\\":{\\\"name\\\":\\\"fbfeywrshgef\\\",\\\"price\\\":455,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/127.0.0.1:8000\\/storage\\/menu_images\\/znLi4RBJpRAxMiVtDHuh9COnJkHvyC9a7ZpqXaGy.jpg\\\"}}\"', 'dfvdf@gmail.com', 554.00, 'completed', '2026-02-25 06:08:47', '2026-02-27 00:26:34'),
(3, 1, '3w4etyui', '2345678901', 'rsetxdrcgvhjbjhhgfds', '\"{\\\"Garlic bread\\\":{\\\"name\\\":\\\"Garlic bread\\\",\\\"price\\\":119,\\\"qty\\\":2,\\\"img\\\":\\\"http:\\/\\/10.54.31.156:8000\\/storage\\/menu_images\\/yHnZwHbaCv67VKD0ekZU4D0kHCSKRUEITETxnEit.jpg\\\"}}\"', 'vaibhavi2226@gmail.com', 238.00, 'completed', '2026-02-25 23:34:48', '2026-03-06 04:29:01'),
(4, 2, 'khukhu', '5357899654', 'vxujabuxj', '\"{\\\"fbfeywrshgef\\\":{\\\"name\\\":\\\"fbfeywrshgef\\\",\\\"price\\\":455,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.54.31.156:8000\\/storage\\/menu_images\\/znLi4RBJpRAxMiVtDHuh9COnJkHvyC9a7ZpqXaGy.jpg\\\"}}\"', 'khukhu@gmail.com', 455.00, 'completed', '2026-02-26 00:48:44', '2026-02-27 01:49:52'),
(5, 1, 'ggg', '5357899654', 'sdfgbhjertyuiobnm,', '\"{\\\"Maggi\\\":{\\\"name\\\":\\\"Maggi\\\",\\\"price\\\":89,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.54.31.156:8000\\/storage\\/menu_images\\/ABsEH7ynBJqCjeK0QRJEqyltXBIZDqcOdBfNr0jC.png\\\"}}\"', 'ggg@gmail.com', 89.00, 'cancelled', '2026-02-26 00:52:52', '2026-02-26 23:54:53'),
(6, 1, 'xcvbnu', '5357899654', 'cvbhytresx ml', '\"{\\\"noodles\\\":{\\\"name\\\":\\\"noodles\\\",\\\"price\\\":50,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.54.31.156:8000\\/storage\\/menu_images\\/Olne6pgXwsMnQnPCHJXBYdv6ev6ajiNGmuNfuGhS.jpg\\\"}}\"', 'ggg@gmail.com', 50.00, 'completed', '2026-02-26 00:53:15', '2026-02-27 01:49:52'),
(7, 1, 'bbb', '6767898934', 'nvruikmvrtyuiol,ms', '\"{\\\"Maggi\\\":{\\\"name\\\":\\\"Maggi\\\",\\\"price\\\":89,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/192.168.1.46:8000\\/storage\\/menu_images\\/ABsEH7ynBJqCjeK0QRJEqyltXBIZDqcOdBfNr0jC.png\\\"}}\"', 'bbb@gmail.com', 89.00, 'completed', '2026-02-27 00:33:06', '2026-03-06 04:29:01'),
(8, 2, 'simran', '5357899654', 'cvbhytresx ml', '\"{\\\"Maggi\\\":{\\\"name\\\":\\\"Maggi\\\",\\\"price\\\":89,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/192.168.1.46:8000\\/storage\\/menu_images\\/ABsEH7ynBJqCjeK0QRJEqyltXBIZDqcOdBfNr0jC.png\\\"},\\\"fbfeywrshgef\\\":{\\\"name\\\":\\\"fbfeywrshgef\\\",\\\"price\\\":455,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/192.168.1.46:8000\\/storage\\/menu_images\\/znLi4RBJpRAxMiVtDHuh9COnJkHvyC9a7ZpqXaGy.jpg\\\"}}\"', 'gwansik@gmail.com', 544.00, 'preparing', '2026-02-27 02:47:53', '2026-03-06 22:43:13'),
(9, 1, '3w4etyui', '2345678901', 'rsetxdrcgvhjbjhhgfds', '\"{\\\"noodles\\\":{\\\"name\\\":\\\"noodles\\\",\\\"price\\\":50,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.54.31.156:8000\\/storage\\/menu_images\\/Olne6pgXwsMnQnPCHJXBYdv6ev6ajiNGmuNfuGhS.jpg\\\"}}\"', 'vaibhavi2226@gmail.com', 50.00, 'completed', '2026-03-06 03:50:58', '2026-03-06 04:29:01'),
(10, 4, 'karina', '9925255275', 'asdfs, ju', '\"{\\\"Chocolate Lava Cake\\\":{\\\"name\\\":\\\"Chocolate Lava Cake\\\",\\\"price\\\":420,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/192.168.1.44:8000\\/storage\\/menu_images\\/e64g8ETsRXYaJk8XYLODhFuw3YvoRsmGIODHw0X4.jpg\\\"},\\\"Verdure Pizza\\\":{\\\"name\\\":\\\"Verdure Pizza\\\",\\\"price\\\":620,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/192.168.1.44:8000\\/storage\\/menu_images\\/XhqC6q9J8jyiDJ3asoS1jwOUjcXNZCilzIT2Dvy0.webp\\\"}}\"', 'info@toroni.in', 1040.00, 'pending', '2026-03-09 04:55:36', '2026-03-09 04:55:36'),
(11, 1, 'karina', '9871123465', 'asdfs, ju', '\"{\\\"Classic Tiramisu\\\":{\\\"name\\\":\\\"Classic Tiramisu\\\",\\\"price\\\":420,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/TPTT8QjFbnd3rU6Nu9b45INvIMIMYiPI8wTf7lCJ.webp\\\"}}\"', 'pkhushali123@outlook.com', 420.00, 'pending', '2026-03-11 04:36:01', '2026-03-11 04:36:01'),
(12, 1, 'karina', '9871123465', 'asdfs, ju', '\"{\\\"Spaghetti Aglio e Olio\\\":{\\\"name\\\":\\\"Spaghetti Aglio e Olio\\\",\\\"price\\\":480,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/xQid9ShM2m2wUwZ69bkOMzwOYugD36PzkoeGeNHI.jpg\\\"},\\\"Margherita Pizza\\\":{\\\"name\\\":\\\"Margherita Pizza\\\",\\\"price\\\":480,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/FVrprmOoXmQGuouCrM23gsThrPYtyH0I5eH8mcad.jpg\\\"},\\\"Chesse Garlic Bread\\\":{\\\"name\\\":\\\"Chesse Garlic Bread\\\",\\\"price\\\":503,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/H5zilzvRPGqyFA12btxbHwDeR1KWcLu5hiTkoRHa.jpg\\\"}}\"', 'pkhushali123@outlook.com', 1463.00, 'pending', '2026-03-11 04:37:05', '2026-03-11 04:37:05'),
(13, 2, 'karina', '9871123465', 'asdfs, ju', '\"{\\\"Diavola Pizza\\\":{\\\"name\\\":\\\"Diavola Pizza\\\",\\\"price\\\":650,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/HZUGJJdEltvP1BevsuGimZfL4ShBdC1CtfLrtZth.jpg\\\"}}\"', 'pkhushali123@outlook.com', 650.00, 'pending', '2026-03-11 04:37:26', '2026-03-11 04:37:26'),
(14, 1, 'VAIBHAVI RAJESHKUMAR PANCHAL', '9978222004', '24 Durga Enclave, Vadsar Kalali ring road, Vadodara', '\"{\\\"Spaghetti Aglio e Olio\\\":{\\\"name\\\":\\\"Spaghetti Aglio e Olio\\\",\\\"price\\\":480,\\\"qty\\\":2,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/xQid9ShM2m2wUwZ69bkOMzwOYugD36PzkoeGeNHI.jpg\\\"},\\\"Garlic bread\\\":{\\\"name\\\":\\\"Garlic bread\\\",\\\"price\\\":700,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/i8LZzZfpoLnQHlq3zSCbKyxruSHgdW8MBNs0LyW0.jpg\\\"},\\\"Chesse Garlic Bread\\\":{\\\"name\\\":\\\"Chesse Garlic Bread\\\",\\\"price\\\":503,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/H5zilzvRPGqyFA12btxbHwDeR1KWcLu5hiTkoRHa.jpg\\\"}}\"', 'vaibhavi2226@gmail.com', 2163.00, 'pending', '2026-03-11 04:39:40', '2026-03-11 04:39:40'),
(15, 1, 'karina', '9871123465', 'asdfs, ju', '\"{\\\"Classic Tiramisu\\\":{\\\"name\\\":\\\"Classic Tiramisu\\\",\\\"price\\\":420,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/TPTT8QjFbnd3rU6Nu9b45INvIMIMYiPI8wTf7lCJ.webp\\\"}}\"', 'pkhushali123@outlook.com', 420.00, 'pending', '2026-03-11 04:44:44', '2026-03-11 04:44:44'),
(16, 1, 'Vaibhavi Panchal', '9978222004', 'Durga Enclave', '\"{\\\"Classic Tiramisu\\\":{\\\"name\\\":\\\"Classic Tiramisu\\\",\\\"price\\\":420,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/TPTT8QjFbnd3rU6Nu9b45INvIMIMYiPI8wTf7lCJ.webp\\\"},\\\"tyyd\\\":{\\\"name\\\":\\\"tyyd\\\",\\\"price\\\":689,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/mV5MnhB6lRSbQI3hV3Uu27kVBYqVzRNHvfp92iIA.jpg\\\"},\\\"davsD\\\":{\\\"name\\\":\\\"davsD\\\",\\\"price\\\":4543,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/aMS7P5W6MeIGf4374jfymVbM8fjV1mEiI2wN5Za1.jpg\\\"},\\\"hhhaaa\\\":{\\\"name\\\":\\\"hhhaaa\\\",\\\"price\\\":789,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/vIiWXZthKuYYQ2FpAQQrtB7Ag7mjA5VZcHEw6ndv.jpg\\\"},\\\"gw\\\":{\\\"name\\\":\\\"gw\\\",\\\"price\\\":3,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/jJhMTeZuugj94Ovfiw5HtwuceSteqJcaxJ28xSmB.jpg\\\"},\\\"burata\\\":{\\\"name\\\":\\\"burata\\\",\\\"price\\\":432,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/K5C4US4K9EmdC32MEeYaazoNzgGhLrc0CvWrnr0G.jpg\\\"},\\\"Garlic bread\\\":{\\\"name\\\":\\\"Garlic bread\\\",\\\"price\\\":700,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/i8LZzZfpoLnQHlq3zSCbKyxruSHgdW8MBNs0LyW0.jpg\\\"},\\\"Spaghetti Aglio e Olio\\\":{\\\"name\\\":\\\"Spaghetti Aglio e Olio\\\",\\\"price\\\":480,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/xQid9ShM2m2wUwZ69bkOMzwOYugD36PzkoeGeNHI.jpg\\\"}}\"', 'DouglasThurston@theCloneStore660.onmicrosoft.com', 8056.00, 'pending', '2026-03-11 04:45:32', '2026-03-11 04:45:32'),
(17, 4, 'xc wr', '9978333449', 'scwcdw', '\"{\\\"Chocolate Lava Cake\\\":{\\\"name\\\":\\\"Chocolate Lava Cake\\\",\\\"price\\\":420,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/127.0.0.1:8000\\/storage\\/menu_images\\/e64g8ETsRXYaJk8XYLODhFuw3YvoRsmGIODHw0X4.jpg\\\"},\\\"Creamy Alfredo Pasta\\\":{\\\"name\\\":\\\"Creamy Alfredo Pasta\\\",\\\"price\\\":560,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/127.0.0.1:8000\\/storage\\/menu_images\\/hSCqmk8cKoKYMIa8hsLlBj4bB9b6SNMMpUkiM8os.jpg\\\"},\\\"Verdure Pizza\\\":{\\\"name\\\":\\\"Verdure Pizza\\\",\\\"price\\\":620,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/127.0.0.1:8000\\/storage\\/menu_images\\/XhqC6q9J8jyiDJ3asoS1jwOUjcXNZCilzIT2Dvy0.webp\\\"}}\"', 'vaibhu@gmail.com', 1600.00, 'pending', '2026-03-12 00:01:24', '2026-03-12 00:01:24'),
(18, 4, 'So', '9978222004', 'Vadsar Vadodara', '\"{\\\"Creamy Alfredo Pasta\\\":{\\\"name\\\":\\\"Creamy Alfredo Pasta\\\",\\\"price\\\":560,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/hSCqmk8cKoKYMIa8hsLlBj4bB9b6SNMMpUkiM8os.jpg\\\"}}\"', 'mpvaibhavipanchal001080@gmail.com', 560.00, 'pending', '2026-03-12 23:16:51', '2026-03-12 23:16:51'),
(19, 4, 'Jaini', '8844339988', 'Maninagar', '\"{\\\"Chocolate Lava Cake\\\":{\\\"name\\\":\\\"Chocolate Lava Cake\\\",\\\"price\\\":420,\\\"qty\\\":4,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/e64g8ETsRXYaJk8XYLODhFuw3YvoRsmGIODHw0X4.jpg\\\"},\\\"Creamy Alfredo Pasta\\\":{\\\"name\\\":\\\"Creamy Alfredo Pasta\\\",\\\"price\\\":560,\\\"qty\\\":4,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/hSCqmk8cKoKYMIa8hsLlBj4bB9b6SNMMpUkiM8os.jpg\\\"},\\\"Verdure Pizza\\\":{\\\"name\\\":\\\"Verdure Pizza\\\",\\\"price\\\":620,\\\"qty\\\":4,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/XhqC6q9J8jyiDJ3asoS1jwOUjcXNZCilzIT2Dvy0.webp\\\"}}\"', 'jainil@gmail.com', 6400.00, 'pending', '2026-03-13 03:48:47', '2026-03-13 03:48:47'),
(20, 2, 'karina', '9871123465', 'asdfs, ju', '\"{\\\"Sicilian Cannoli\\\":{\\\"name\\\":\\\"Sicilian Cannoli\\\",\\\"price\\\":390,\\\"qty\\\":2,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/8uCzC8Qd6Ri0LwT6NbrSqKh4gw79YNtowdCmjwoC.jpg\\\"},\\\"Diavola Pizza\\\":{\\\"name\\\":\\\"Diavola Pizza\\\",\\\"price\\\":650,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/HZUGJJdEltvP1BevsuGimZfL4ShBdC1CtfLrtZth.jpg\\\"},\\\"Penne Arrabbiata\\\":{\\\"name\\\":\\\"Penne Arrabbiata\\\",\\\"price\\\":500,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/gOHol2neECRZXOVEd1n1jnJECeIJQSdYnPZiuuMP.jpg\\\"}}\"', 'pkhushali123@outlook.com', 1930.00, 'pending', '2026-03-13 23:17:54', '2026-03-13 23:17:54'),
(21, 1, 'Biryani', '7046370809', 'Daman', '\"{\\\"Classic Tiramisu\\\":{\\\"name\\\":\\\"Classic Tiramisu\\\",\\\"price\\\":420,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/TPTT8QjFbnd3rU6Nu9b45INvIMIMYiPI8wTf7lCJ.webp\\\"},\\\"Margherita Pizza\\\":{\\\"name\\\":\\\"Margherita Pizza\\\",\\\"price\\\":480,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/10.102.173.156:8000\\/storage\\/menu_images\\/FVrprmOoXmQGuouCrM23gsThrPYtyH0I5eH8mcad.jpg\\\"}}\"', 'gaganoffice443@gmail.com', 900.00, 'pending', '2026-03-15 23:47:24', '2026-03-15 23:47:24'),
(22, 1, 'srgd', '8780757410', 'sxdcfhjnk', '\"{\\\"Margherita Pizza\\\":{\\\"name\\\":\\\"Margherita Pizza\\\",\\\"price\\\":480,\\\"qty\\\":1,\\\"img\\\":\\\"http:\\/\\/127.0.0.1:8000\\/storage\\/menu_images\\/FVrprmOoXmQGuouCrM23gsThrPYtyH0I5eH8mcad.jpg\\\"}}\"', 'ersty@gmail.com', 480.00, 'pending', '2026-03-16 23:11:01', '2026-03-16 23:11:01');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('mss25092005@gmail.com', 'iazEf9VlmebvO6OfOyPkPjDrmp4hcB3DulbTMQ9yWYVMrPhGroJ36XUnHW4N', '2026-03-11 02:41:03'),
('toroni@gmail.com', 'jFcS09EjoUSC2ddtvCwDaQ7LIN15zUvt7YUFv1Peg3RrOdUptwAafuyPrqy3', '2026-03-10 03:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int(11) NOT NULL,
  `franchise_id` int(11) NOT NULL,
  `upi_name` varchar(255) DEFAULT NULL,
  `qr_image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `franchise_id`, `upi_name`, `qr_image`, `created_at`, `updated_at`) VALUES
(5, 1, 'btrfdc', 'images/payments/qr/1772863965_alfredo pasta.png', '2026-02-28 04:51:50', '2026-03-07 00:42:45'),
(6, 2, 'restdryhkjl', 'images/payments/qr/1773468192_upload.jpg', '2026-03-06 03:52:54', '2026-03-14 00:33:12'),
(7, 3, 'ytdsxc', 'images/payments/qr/1772788993_spring roll.jpg', '2026-03-06 03:53:13', '2026-03-06 03:53:13'),
(8, 4, 'fdavab abvdz', 'images/payments/qr/1772790593_ChatGPT Image May 23, 2025, 02_38_08 PM.png', '2026-03-06 04:19:53', '2026-03-06 04:19:53');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(10) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `no_of_people` int(11) NOT NULL,
  `franchise_id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('pending','approved','cancelled','completed') NOT NULL,
  `amount` int(11) DEFAULT 1000,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `payment_status` enum('pending','approved') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `full_name`, `phone_no`, `date`, `time`, `no_of_people`, `franchise_id`, `table_id`, `created_at`, `updated_at`, `status`, `amount`, `transaction_id`, `payment_proof`, `payment_status`) VALUES
(5, 'simran', '1234567890', '2026-02-26', '19:00:00', 4, 2, NULL, '2026-02-26 01:03:14', '2026-02-26 01:59:04', 'completed', 1000, 'nbvfd4567uio0pokm', 'images/payments/1772087594_alfredo pasta.png', 'approved'),
(6, 'khukhu', '4567276789', '2026-02-27', '20:00:00', 4, 1, NULL, '2026-02-26 01:04:00', '2026-02-26 01:59:04', 'completed', 1000, 'mgt67uhbn098u', 'images/payments/1772087640_spring roll.jpg', 'approved'),
(7, 'ggg', '6767585848', '2026-03-18', '19:30:00', 2, 2, NULL, '2026-03-01 23:27:13', '2026-03-01 23:27:13', 'pending', 1000, 'hgt678ij', 'images/payments/1772427433_spring roll.jpg', 'pending'),
(8, 'sfdghuj', '9978222005', '2026-03-10', '12:00:00', 4, 1, NULL, '2026-03-02 00:32:29', '2026-03-02 00:32:29', 'pending', 1000, 'sfgbfnyjhgfds', 'images/payments/transaction1772431349_cd.jpg', 'pending'),
(9, 'wefGRENWYR', '8873456003', '2026-03-28', '21:00:00', 3, 1, 10, '2026-03-13 01:00:49', '2026-03-13 01:57:58', 'approved', 1000, 'dhgjktyrshtxfcgyyu', 'images/payments/transaction1773383449_img (1).jpg', 'approved'),
(10, 'dsfds', '1234567898', '2026-03-22', '12:00:00', 2, 1, 10, '2026-03-13 23:02:26', '2026-03-13 23:02:26', 'pending', 1000, 'werewrwe5465464', 'images/payments/transaction1773462746_WhatsApp Image 2026-03-11 at 10.48.25.jpeg', 'pending'),
(11, 'dfsdf', '1234567898', '2026-03-22', '12:30:00', 2, 1, 10, '2026-03-13 23:03:09', '2026-03-13 23:03:09', 'pending', 1000, 'werewrwe5465464', 'images/payments/transaction1773462789_Wallpaper iPhone zootopia.jpg', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_tables`
--

CREATE TABLE `restaurant_tables` (
  `id` int(10) NOT NULL,
  `franchise_id` int(10) NOT NULL,
  `table_no` int(5) NOT NULL,
  `status` enum('available','not available') NOT NULL,
  `capacity_people` int(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant_tables`
--

INSERT INTO `restaurant_tables` (`id`, `franchise_id`, `table_no`, `status`, `capacity_people`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'not available', 2, NULL, '2026-03-09 04:45:15'),
(2, 1, 2, 'available', 4, NULL, '2026-03-09 04:45:03'),
(3, 1, 3, 'not available', 6, NULL, '2026-03-13 04:06:55'),
(4, 1, 4, 'available', 6, NULL, '2026-03-09 04:45:03'),
(5, 2, 1, 'not available', 2, NULL, '2026-03-13 04:36:28'),
(6, 2, 2, 'available', 2, NULL, NULL),
(7, 2, 3, 'available', 4, NULL, '2026-03-06 03:57:36'),
(8, 2, 4, 'available', 6, NULL, NULL),
(9, 3, 1, 'available', 2, NULL, '2026-02-28 04:34:59'),
(10, 1, 5, 'available', 3, '2026-02-27 03:39:32', '2026-03-13 04:36:02'),
(11, 4, 1, 'available', 5, '2026-03-06 04:26:42', '2026-03-06 04:27:16'),
(13, 4, 2, 'available', 6, '2026-03-06 04:27:35', '2026-03-06 04:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `franchise_id` int(11) NOT NULL,
  `cust_name` varchar(255) NOT NULL,
  `review_text` text NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `franchise_id`, `cust_name`, `review_text`, `rating`, `created_at`, `updated_at`) VALUES
(7, 1, 'Riya Shah', 'Absolutely loved the food at Toroni! The pasta was perfectly cooked and the pizza had an authentic Italian taste. The ambiance is cozy and perfect for a dinner with friends or family. The staff was also very polite and attentive.', 5, '2026-03-09 01:44:42', '2026-03-09 01:44:42'),
(8, 2, 'Amit Patel', 'Great place for Italian food. The Alfredo pasta and garlic bread were really good. Service was quick and the presentation of dishes was nice. Slightly pricey but worth the experience.', 4, '2026-03-09 01:45:24', '2026-03-09 01:45:24'),
(9, 3, 'Neha Mehta', 'One of the best Italian restaurants I’ve visited! The wood-fired pizza was amazing and the tiramisu dessert was the highlight. The restaurant has a very classy vibe and the staff makes you feel welcome.', 5, '2026-03-09 01:46:12', '2026-03-09 01:46:12');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `opening_hours` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `email`, `contact`, `address`, `opening_hours`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'info@toroni.in', '9925255275', '2nd Floor, A-Square, Sardar Patel Ring Rd, near Shilaj Circle, Shilaj, Ahmedabad, Gujarat 380059, India', '12:00 PM – 11:30 PM', 'images/logo/logo_1773045261.jpg', '2026-03-07 00:06:06', '2026-03-10 01:01:53');

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` int(11) NOT NULL,
  `franchise_id` int(11) NOT NULL,
  `story_img` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `franchise_id`, `story_img`, `created_at`, `updated_at`) VALUES
(7, 1, 'images/story/1773040273_story.png', '2026-03-09 01:41:13', '2026-03-09 01:41:13'),
(8, 2, 'images/story/1773040290_story.png', '2026-03-09 01:41:30', '2026-03-09 01:41:30'),
(9, 3, 'images/story/1773040303_story.png', '2026-03-09 01:41:43', '2026-03-09 01:41:43'),
(11, 4, 'images/story/1773386388_story.jpg', '2026-03-13 01:49:48', '2026-03-13 01:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `franchise_id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `role` enum('Manager','Chef','Waiter','Receptionist','Staff') DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `franchise_id`, `name`, `role`, `image`, `description`, `created_at`, `updated_at`) VALUES
(3, 1, 'Marco Bianchi', 'Chef', '1773044908.jpg', 'Marco leads the kitchen at Toronee and focuses on authentic Italian flavors, handmade pasta, and wood-fired pizza recipes inspired by traditional Italian cooking.', '2026-03-09 01:52:12', '2026-03-16 00:03:48'),
(4, 2, 'Sofia Romano', 'Chef', '1773044896.jpg', 'Sofia works closely with the head chef and specializes in pasta preparation, fresh sauces, and plating Italian dishes beautifully.', '2026-03-09 01:52:52', '2026-03-09 02:58:16'),
(5, 3, 'Alessandro Rossi', 'Manager', '1773044881.jpg', 'Alessandro oversees the restaurant’s daily operations, ensuring smooth service and a welcoming dining experience for guests.', '2026-03-09 01:53:15', '2026-03-09 02:58:01'),
(6, 4, 'Matteo Greco', 'Chef', '1773044681.jpg', 'Matteo is responsible for preparing authentic Italian pizzas using traditional dough fermentation and fresh toppings.', '2026-03-09 01:53:47', '2026-03-09 02:54:41'),
(7, 1, 'Luca Ferraro', 'Staff', '1773044668.jpg', 'Luca manages the front-of-house team and ensures high-quality customer service for every guest.', '2026-03-09 01:54:17', '2026-03-09 02:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `franchises`
--
ALTER TABLE `franchises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `franchise_partners`
--
ALTER TABLE `franchise_partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_franchise` (`franchise_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_franchise` (`franchise_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `franchise_id` (`franchise_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservation_franchise` (`franchise_id`),
  ADD KEY `fk_reservation_table` (`table_id`);

--
-- Indexes for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_franchise_table` (`franchise_id`,`table_no`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reviews_franchise` (`franchise_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stories_franchise` (`franchise_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_team_members_franchise` (`franchise_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `franchises`
--
ALTER TABLE `franchises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `franchise_partners`
--
ALTER TABLE `franchise_partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_items_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD CONSTRAINT `fk_payment_settings_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_table` FOREIGN KEY (`table_id`) REFERENCES `restaurant_tables` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservations_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restaurant_tables`
--
ALTER TABLE `restaurant_tables`
  ADD CONSTRAINT `fk_restaurant_tables_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stories`
--
ALTER TABLE `stories`
  ADD CONSTRAINT `fk_stories_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `fk_team_members_franchise` FOREIGN KEY (`franchise_id`) REFERENCES `franchises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
