-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 31, 2025 at 11:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_message`
--

CREATE TABLE `admin_message` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `message_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `rent_price` int(11) NOT NULL,
  `availability_status` enum('available','rented') DEFAULT 'available',
  `user_id` int(11) DEFAULT NULL,
  `add_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `book_condition` varchar(50) DEFAULT NULL,
  `address2` varchar(255) NOT NULL,
  `language` varchar(50) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `contact_info` varchar(100) DEFAULT NULL,
  `pickup_method` varchar(50) DEFAULT NULL,
  `book_writer` varchar(255) NOT NULL,
  `book_publisher` varchar(255) NOT NULL,
  `isbn_10` varchar(10) NOT NULL,
  `isbn_13` varchar(13) NOT NULL,
  `rented` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `book_id`, `title`, `author`, `genre`, `image_path`, `rent_price`, `availability_status`, `user_id`, `add_date`, `description`, `book_condition`, `address2`, `language`, `pages`, `full_name`, `contact_info`, `pickup_method`, `book_writer`, `book_publisher`, `isbn_10`, `isbn_13`, `rented`) VALUES
(1, NULL, 'wings of fire', 'APJ.ABDUL KALAM', 'Biography', 'uploads/book_67c8636d3a6469.70416842.png', 10, 'rented', 27, '2025-03-05', 'hhh', 'Like New', '', 'English', 122, 'Faiej Kha', 'faiz@123', 'Self Pickup', 'APJ.ABDUL KALAM', 'APJ.ABDUL KALAM', '8193446812', '917-819344681', 0),
(2, NULL, 'wings of fire', 'APJ.ABDUL KALAM', 'Biography', 'uploads/book_67c9461eb861f3.19603011.png', 149, 'rented', 26, '2025-03-06', 'Wings of Fire** is an autobiography of Dr. A.P.J. Abdul Kalam, India\\\'s former President and renowned scientist. The book narrates his journey from a small-town boy in Rameswaram to becoming a key figure in India\\\'s missile and space programs. It highlights his struggles, achievements, and contributions to India\\\'s defense and space technology, inspiring young minds to dream big and work hard.', 'New', '', 'English', 123, 'WINGS OF FIRE', 'jitu@gmail.com', 'Self Pickup', 'APJ.ABDUL KALAM', 'Newera Publishing House;', '9350337290', '979-888772956', 0),
(5, NULL, 'Nelson Mandela', 'Peter Limb', 'Biography', 'uploads/book_67c947ca470cf6.47002415.jpg', 300, 'available', 26, '2025-03-06', 'His quest for racial justice for black South Africans as a leader of the African National Congress led to 27 years of imprisonment. Following intense international pressure on the Apartheid government, he was finally freed in 1990. Everyone should know the life story of Nelson Mandela, one of the greatest leaders of all time, the first black president of South Africa and a major world statesman. His inspiring life receives a fresh retelling in this new biography written especially for general readers and students. This volume is an authoritative and enjoyable way not only to understand a great man, but also to understand a critical time in world history and race relations. Through the landmark presidency of South Africa and post Nobel Peace Prize years, up until today, he has continued as a peacemaker and agent for change. A timeline, photo essay and selected bibliography complement the narrative.', 'Like New', '', 'English', 256, 'Nelson Mandela', 'alokavhare@gmail.com', 'Self Pickup', 'Peter Limb', '‎ White Falcon Publishing', '5585787778', '978-819344681', 0),
(6, NULL, 'Captain Underpants Three Pant Tastic Novel In One 3 In 1', 'Dav Pilkey', 'Non-fiction', 'uploads/book_67c949123ae887.82016557.jpg', 499, 'available', 26, '2025-03-06', 'Captain Underpants: Three Pant-Tastic Novels in One is a collection of three hilarious books from Dav Pilkey’s Captain Underpants series. This 3-in-1 edition features the adventures of George and Harold, two prank-loving kids who accidentally turn their school principal, Mr. Krupp, into the superhero Captain Underpants. Filled with action, humor, and flip-o-rama fun, this collection is perfect for young readers who enjoy silly and exciting stories. 🚀😂\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n\\r\\n', 'Good', '', 'English', 187, 'Captain Underpants Three Pant Tastic Novel In One 3 In 1', 'alokavhare@gmail.com', 'Self Pickup', 'Dav Pilkey', 'Gardners Books', '9876543217', '979-987654321', 0),
(9, NULL, 'shyam chi aai', 'Swami Vivekanand', 'Biography', 'uploads/book_67c94d91d05f05.86406490.jpeg', 149, 'available', 26, '2025-03-06', 'Shyam chi aai', 'Like New', '', 'Marathi', 176, 'shyam chi aai', '9838758324', 'Self Pickup', 'Swami Vivekanand', 'Notion Press', '9878987432', '978-987898743', 0),
(10, NULL, 'Budhbhushan', 'Chatrapati Sambhaji Maharaj', 'History', 'uploads/book_67c9ba25ac2f37.74859856.jpg', 10, 'available', 41, '2025-03-06', 'book is good', 'Good', '', 'Marathi', 172, 'PRATIBHA CHAVAN', '9022149039', 'Self Pickup', 'Chatrapati Sambhaji Maharaj', 'Jijai Prakashan', '8885750448', '198-888575044', 0),
(11, 11, 'Sherlock Holmes', 'WILCO INTERNATIONAL LLP', 'Mystery', 'uploads/book_67de4a9517af58.38089978.jpg', 10, '', 26, '2025-03-22', 'Sherlock Holmes is the greatest fictional detective in the world. The hero of 56 short stories and four novels, he is so convincing that letters still arrive at 221B Baker Street seeking his help, and when it was thought that he had died in his clash with the evil Professor Moriarty (\\\'the Napoleon of Crime\\\') young men in London wore black armbands. This luxury boxed set contains all of Conan Doyle\\\'s novels and stories: The Return of Sherlock Holmes; The Memoirs of Sherlock Holmes; The Adventures of Sherlock Holmes; The Valley of Fear & His Last Bow; The Case-Book of Sherlock Holmes; The Hound of the Baskervilles; and A Study in Scarlet & The Sign of Four. A must-have for fans of Sherlock Holmes and classic mysteries!', 'Good', '10, lane no 6, dhule, dhule, maharshtra - 424001', 'English', 321, 'JITESH PARDHI', '9022149039', 'Self Pickup', 'WILCO INTERNATIONAL LLP', 'WILCO INTERNATIONAL LLP', '9390213566', '978-939021356', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `book_id`, `title`, `price`, `image_path`) VALUES
(13, 26, 7, 'Physics For Jee Main And Advanced Vol 1', 599.00, 'uploads/book_67c94ad3e85aa6.63445290.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_orders`
--

CREATE TABLE `dispatch_orders` (
  `id` int(11) NOT NULL,
  `source_address` varchar(255) NOT NULL,
  `destination_address` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `distance` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `future_rentals`
--

CREATE TABLE `future_rentals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `book_id` int(11) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `owner_email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `optional_mobile` varchar(15) DEFAULT NULL,
  `return_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `return_time` time DEFAULT '17:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `token`, `book_id`, `customer_email`, `owner_email`, `full_name`, `address`, `city`, `pincode`, `mobile`, `optional_mobile`, `return_date`, `created_at`, `return_time`) VALUES
(12, '4f329e538d07e87c77181e8854cd2fcb', 11, 'alokavhare2@gmail.com', 'jitupardhi2006@gmail.com', 'Alok Avhare', 'Aadhar nagar deopur dhule', 'Dhule', '420042', '9309276772', '9511200230', '2025-04-02', '2025-03-31 20:35:09', '02:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `id` int(11) NOT NULL,
  `sender_name` varchar(100) NOT NULL,
  `sender_address` text NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_address` text NOT NULL,
  `shipping_charge` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcels`
--

INSERT INTO `parcels` (`id`, `sender_name`, `sender_address`, `receiver_name`, `receiver_address`, `shipping_charge`, `created_at`) VALUES
(1, 'JITESH PARDHI', 'shule', 'Alok Avhare', 'dhule', 50.00, '2025-03-31 19:45:36'),
(2, 'JITESH PARDHI', 'shule', 'Alok Avhare', 'dhule', 50.00, '2025-03-31 19:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_photo` varchar(255) DEFAULT 'default-profile.png',
  `role` varchar(50) DEFAULT NULL,
  `library_type` varchar(255) DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `library_address` text DEFAULT NULL,
  `verification_token` varchar(64) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `last_name`, `email`, `mobile_number`, `address`, `password`, `created_at`, `profile_photo`, `role`, `library_type`, `opening_time`, `closing_time`, `library_address`, `verification_token`, `verified`) VALUES
(26, '', 'JITESH ', 'PARDHI', 'jitupardhi2006@gmail.com', '8446547247', 'dhule', '$2y$10$6d5XqjLjmqRUPhxxmdhpsOdXKDp/PiwnAf3W9jwAVCeETRduH.Jpy', '2025-03-05 13:16:53', 'default-profile.png', 'Customer', NULL, NULL, NULL, NULL, NULL, 0),
(27, '', 'UDAY', 'AHIRE', 'pranav2006mali@gmail.com', '7620154464', 'SONGIR', '$2y$10$f.sxKpgkO00ZDJtkMlR5J.AuhCbyyJS7Ugti/Oxx12J59neoB8p3q', '2025-03-05 13:31:21', 'default-profile.png', 'Customer', NULL, NULL, NULL, NULL, NULL, 0),
(41, '', 'PRATIBHA', NULL, 'chavanpratibha1986@gmail.com', '9022149039', 'DHULE', '$2y$10$vJTCGJup/DFZtlrhYzx6veiD2TWxhaXd4HB3yV6XndnHcUgbzBfqW', '2025-03-10 18:45:27', 'default-profile.png', 'customer', NULL, NULL, NULL, NULL, NULL, 0),
(43, '', 'ALOK', 'AVHARE', 'alokavhare2@gmail.com', '9309276772', 'DHULE', '$2y$10$Pgub3jl3U86THO4p6wrl2.YHu6Wkm8JsyzrMBqHKf8I4s7sA0UBn6', '2025-03-17 07:25:30', 'default-profile.png', 'customer', NULL, NULL, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `view_requests`
--

CREATE TABLE `view_requests` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `valid_until` timestamp NOT NULL DEFAULT (current_timestamp() + interval 1 day),
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `view_requests`
--

INSERT INTO `view_requests` (`id`, `book_id`, `customer_email`, `token`, `created_at`, `valid_until`, `status`) VALUES
(35, 11, 'alokavhare2@gmail.com', '9e50fc8183bce22c9e312aee8f2c6c8d', '2025-03-31 17:44:46', '2025-04-01 17:44:46', 'Accepted'),
(36, 11, 'alokavhare2@gmail.com', '4f329e538d07e87c77181e8854cd2fcb', '2025-03-31 20:31:50', '2025-04-01 20:31:50', 'Accepted');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_message`
--
ALTER TABLE `admin_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn_10` (`isbn_10`),
  ADD UNIQUE KEY `isbn_13` (`isbn_13`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispatch_orders`
--
ALTER TABLE `dispatch_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `future_rentals`
--
ALTER TABLE `future_rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `view_requests`
--
ALTER TABLE `view_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `book_id` (`book_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `dispatch_orders`
--
ALTER TABLE `dispatch_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `future_rentals`
--
ALTER TABLE `future_rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `view_requests`
--
ALTER TABLE `view_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_message`
--
ALTER TABLE `admin_message`
  ADD CONSTRAINT `admin_message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `future_rentals`
--
ALTER TABLE `future_rentals`
  ADD CONSTRAINT `future_rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `future_rentals_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

--
-- Constraints for table `view_requests`
--
ALTER TABLE `view_requests`
  ADD CONSTRAINT `view_requests_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
