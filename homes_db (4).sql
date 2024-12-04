-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 06:00 AM
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
-- Database: `homes_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_cred`
--

CREATE TABLE `admin_cred` (
  `sr_no` int(11) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `admin_pass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_cred`
--

INSERT INTO `admin_cred` (`sr_no`, `admin_name`, `admin_pass`) VALUES
(1, 'homes', 'homes123');

-- --------------------------------------------------------

--
-- Table structure for table `carousel`
--

CREATE TABLE `carousel` (
  `sr_no` int(11) NOT NULL,
  `image` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel`
--

INSERT INTO `carousel` (`sr_no`, `image`) VALUES
(14, 'IMG_34840.png'),
(15, 'IMG_79922.png'),
(16, 'IMG_54348.png'),
(17, 'IMG_74330.png'),
(18, 'IMG_30778.png'),
(21, 'IMG_62932.png'),
(27, 'IMG_14502.jpg'),
(28, 'IMG_51806.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `contact_details`
--

CREATE TABLE `contact_details` (
  `sr_no` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `gmap` varchar(100) NOT NULL,
  `pn1` bigint(20) NOT NULL,
  `pn2` bigint(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fb` varchar(100) NOT NULL,
  `insta` varchar(100) NOT NULL,
  `tw` varchar(100) NOT NULL,
  `iframe` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_details`
--

INSERT INTO `contact_details` (`sr_no`, `address`, `gmap`, `pn1`, `pn2`, `email`, `fb`, `insta`, `tw`, `iframe`) VALUES
(1, 'SL METROPOLIS NORTH MAIN HOA OFFICE', 'https://maps.app.goo.gl/L14pV3SAfSzZbFXJ6', 98765432, 0, 'homes.capstone.project@gmail.com', 'https://www.facebook.com/', 'https://www.instagram.com/', '', 'https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1403.243422936668!2d120.79359735836137!3d14.893487538066662!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e1!3m2!1sen!2sph!4v1731140621168!5m2!1sen!2sph');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `area` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` varchar(350) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `removed` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `area`, `price`, `quantity`, `description`, `status`, `removed`) VALUES
(1, 'sample', 1, 1, 1, '123', 0, 1),
(6, 'test 2', 123, 123, 123, '123', 1, 1),
(7, 'court', 10, 250, 10, 'court', 1, 1),
(8, 'basket', 123, 123, 123, '123', 1, 1),
(9, 'swimming', 1000, 100, 100, 'qwerty', 1, 1),
(10, 'Room 1', 123, 150, 2, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis accusamus odio quidem delectus id nihil consequatur quibusdam, ut facere fugiat? Voluptatum quidem nulla assumenda vel illum, distinctio modi sit aliquam.Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis accusamus odio quidem delectus id nihil consequatur quibusda', 1, 1),
(11, 'Swimming pool', 100, 150, 12, '123qweLorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis accusamus odio consequatur qu', 1, 0),
(12, 'r', 1, 1, 1, '1', 1, 1),
(13, 'BasketBall', 12, 120, 12, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. A harum numquam dicta reprehenderit', 1, 0),
(14, 'Club House', 123, 123, 123, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis accusamus odio quidem', 1, 0),
(15, 'tennis', 2, 100, 12, '1234567890rtrtrt', 1, 0),
(16, 'wewq', 12, 12, 12, 'qwertyuytre', 1, 1),
(17, 'Ludin Cator', 12, 1, 1, '123456789', 1, 1),
(18, 'test', 123, 123, 123, 'qqwertyuiop', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `facilities_inclusions`
--

CREATE TABLE `facilities_inclusions` (
  `sr_no` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `inclusions_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities_inclusions`
--

INSERT INTO `facilities_inclusions` (`sr_no`, `facility_id`, `inclusions_id`) VALUES
(88, 13, 17),
(89, 13, 18),
(90, 13, 21),
(91, 13, 22),
(92, 14, 18),
(93, 14, 23),
(113, 15, 18),
(114, 15, 21),
(115, 11, 17),
(116, 11, 18),
(117, 11, 21),
(118, 11, 22),
(119, 11, 23);

-- --------------------------------------------------------

--
-- Table structure for table `facility_images`
--

CREATE TABLE `facility_images` (
  `sr_no` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `image` varchar(150) NOT NULL,
  `thumb` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility_images`
--

INSERT INTO `facility_images` (`sr_no`, `facility_id`, `image`, `thumb`) VALUES
(39, 14, 'IMG_74437.png', 1),
(45, 15, 'IMG_96988.webp', 1),
(48, 11, 'IMG_18969.webp', 1),
(49, 11, 'IMG_22619.jfif', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inclusions`
--

CREATE TABLE `inclusions` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inclusions`
--

INSERT INTO `inclusions` (`id`, `icon`, `name`, `description`) VALUES
(17, 'IMG_97145.svg', 'Sample', 'Lorem ipsum dolor sit amet consectetur adipisicing elit.\r\n            A harum numquam dicta reprehenderit rerum sapiente odio!'),
(18, 'IMG_81499.svg', 'Title', 'Lorem ipsum dolor sit amet consectetur adipisicing elit.\r\n            A harum numquam dicta reprehenderit rerum sapiente odio!'),
(21, 'IMG_51417.svg', 'asim', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. A harum numquam dicta reprehenderit rerum sapiente odio!'),
(22, 'IMG_96813.svg', 'ewan', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis accusamus odio quidem delectus id nihil consequatur quibusdam, ut facere fugiat? Voluptatum quidem nulla assumenda vel illum, distinctio modi sit aliquam.'),
(23, 'IMG_45950.svg', 'test2', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis accusamus odio quidem delectus id nihil consequatur quibusdam, ut facere fugiat? Voluptatum quidem nulla assumenda vel illum, distinctio modi sit aliquam.');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `sr_no` int(11) NOT NULL,
  `site_title` varchar(50) NOT NULL,
  `site_about` varchar(250) NOT NULL,
  `shutdown` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`sr_no`, `site_title`, `site_about`, `shutdown`) VALUES
(1, 'H.O.M.E.S', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis accusamus odio quidem delectus id nihil consequatur quibusdam, ut facere fugiat? Voluptatum quidem nulla assumenda vel illum, distinctio modi sit aliquam.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `team_details`
--

CREATE TABLE `team_details` (
  `sr_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `picture` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_details`
--

INSERT INTO `team_details` (`sr_no`, `name`, `picture`) VALUES
(18, 'sample', 'IMG_40136.jpg'),
(21, 'nix', 'IMG_67850.png'),
(23, 'qwerty', 'IMG_19238.png'),
(24, 'luds', 'IMG_78075.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_cred`
--

CREATE TABLE `user_cred` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(120) NOT NULL,
  `phonenum` varchar(100) NOT NULL,
  `pincode` int(11) NOT NULL,
  `dob` date NOT NULL,
  `profile` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `is_verified` int(11) NOT NULL DEFAULT 0,
  `token` varchar(200) DEFAULT NULL,
  `t_expire` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `datentime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_cred`
--

INSERT INTO `user_cred` (`id`, `name`, `email`, `address`, `phonenum`, `pincode`, `dob`, `profile`, `password`, `is_verified`, `token`, `t_expire`, `status`, `datentime`) VALUES
(16, 'Sample', 'homes.capstone.project@gmail.com', '123', '123', 123, '2024-11-20', 'IMG_44030.jpeg', '$2y$10$vWPBmrh0j9EhjvpHg9BWROrTw4sgh4TeFoxDbF06JySdspD7Sm8ry', 1, NULL, NULL, 1, '2024-11-20 23:27:12'),
(20, 'Ludin Cator', 'ludincator23@gmail.com', '123', '123123', 5432, '2024-11-26', 'IMG_14490.jpeg', '$2y$10$vxVZywvQo7H1MI2ou5NDEejxJQn9LlEyYnCP3EKYGbdUJRwLwQ/Ya', 1, '0d942c637d5dc1e15038eb9dd284f462', NULL, 1, '2024-11-26 09:39:43');

-- --------------------------------------------------------

--
-- Table structure for table `user_queries`
--

CREATE TABLE `user_queries` (
  `sr_no` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` varchar(500) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_cred`
--
ALTER TABLE `admin_cred`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `contact_details`
--
ALTER TABLE `contact_details`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities_inclusions`
--
ALTER TABLE `facilities_inclusions`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `facilities id` (`inclusions_id`),
  ADD KEY `room id` (`facility_id`);

--
-- Indexes for table `facility_images`
--
ALTER TABLE `facility_images`
  ADD PRIMARY KEY (`sr_no`),
  ADD KEY `room_id` (`facility_id`);

--
-- Indexes for table `inclusions`
--
ALTER TABLE `inclusions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `team_details`
--
ALTER TABLE `team_details`
  ADD PRIMARY KEY (`sr_no`);

--
-- Indexes for table `user_cred`
--
ALTER TABLE `user_cred`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_queries`
--
ALTER TABLE `user_queries`
  ADD PRIMARY KEY (`sr_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_cred`
--
ALTER TABLE `admin_cred`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carousel`
--
ALTER TABLE `carousel`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `contact_details`
--
ALTER TABLE `contact_details`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `facilities_inclusions`
--
ALTER TABLE `facilities_inclusions`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `facility_images`
--
ALTER TABLE `facility_images`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `inclusions`
--
ALTER TABLE `inclusions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_details`
--
ALTER TABLE `team_details`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user_cred`
--
ALTER TABLE `user_cred`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user_queries`
--
ALTER TABLE `user_queries`
  MODIFY `sr_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facilities_inclusions`
--
ALTER TABLE `facilities_inclusions`
  ADD CONSTRAINT `facilities id` FOREIGN KEY (`inclusions_id`) REFERENCES `inclusions` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `room id` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `facility_images`
--
ALTER TABLE `facility_images`
  ADD CONSTRAINT `facility_images_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
