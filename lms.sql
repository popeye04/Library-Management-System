-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 02:26 PM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `nationality` varchar(20) DEFAULT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `death_year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `name`, `nationality`, `birth_year`, `death_year`) VALUES
(1, 'Humayun Ahmed', 'Bangladeshi', 1948, 2012),
(2, 'Rabindranath Tagore', 'Indian', 1861, 1941),
(3, 'Kazi Nazrul', 'Bangladeshi', 1899, 1976),
(4, 'J.K. Rowling', 'British', 1965, NULL),
(5, 'George Orwell', 'British', 1903, 1950),
(6, 'Jane Austen', 'British', 1775, 1817),
(7, 'Mark Twain', 'American', 1835, 1910),
(8, 'Ernest Hemingway', 'American', 1899, 1961),
(9, 'Agatha Christie', 'British', 1890, 1976),
(10, 'Dan Brown', 'American', 1964, NULL),
(11, 'Leo Tolstoy', 'Russian', 1828, 1910),
(12, 'Fyodor Dostoevsky', 'Russian', 1821, 1881),
(13, 'Gabriel Garcia', 'Colombian', 1927, 2014),
(14, 'Paulo Coelho', 'Brazilian', 1947, NULL),
(15, 'Stephen King', 'American', 1947, NULL),
(16, 'Arthur Conan', 'British', 1859, 1930),
(17, 'J.R.R. Tolkien', 'British', 1892, 1973),
(18, 'Haruki Murakami', 'Japanese', 1949, NULL),
(19, 'Chetan Bhagat', 'Indian', 1974, NULL),
(20, 'Sunil Gangopadhyay', 'Indian', 1934, 2012);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `publication_year` int(11) DEFAULT NULL,
  `copies_total` int(11) DEFAULT NULL,
  `copies_available` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `category_id`, `publisher_id`, `publication_year`, `copies_total`, `copies_available`) VALUES
(1, 'Pride and Prejudice', 11, 1, 1813, 6, 4),
(2, 'The Notebook', 11, 3, 1996, 5, 3),
(3, 'Me Before You', 11, 2, 2012, 4, 2),
(4, 'Harry Potter and the Sorcerer\'s Stone', 9, 4, 1997, 8, 6),
(5, 'The Hobbit', 9, 5, 1937, 7, 5),
(6, 'Percy Jackson: The Lightning Thief', 9, 2, 2005, 6, 4),
(7, 'Sherlock Holmes: A Study in Scarlet', 10, 6, 1887, 5, 3),
(8, 'Gone Girl', 10, 3, 2012, 4, 1),
(9, 'The Girl with the Dragon Tattoo', 10, 4, 2005, 5, 2),
(10, 'A Brief History of Time', 3, 7, 1988, 6, 4),
(11, 'Cosmos', 3, 5, 1980, 5, 2),
(12, 'The Elegant Universe', 3, 1, 1999, 4, 2),
(13, 'Clean Code', 4, 2, 2008, 7, 5),
(14, 'Introduction to Algorithms', 4, 8, 2009, 6, 3),
(15, 'Computer Networking: A Top-Down Approach', 4, 6, 2016, 5, 3),
(16, 'Sapiens: A Brief History of Humankind', 5, 9, 2011, 7, 4),
(17, 'Guns, Germs, and Steel', 5, 2, 1997, 5, 2),
(18, 'The Silk Roads', 5, 5, 2015, 4, 1),
(19, 'The Diary of a Young Girl', 6, 3, 1947, 6, 4),
(20, 'Long Walk to Freedom', 6, 1, 1994, 5, 2),
(21, 'Steve Jobs', 6, 7, 2011, 4, 3),
(22, 'Meditations', 7, 8, 1980, 6, 5),
(23, 'Beyond Good and Evil', 7, 6, 1886, 5, 2),
(24, 'The Republic', 7, 4, 1980, 6, 4),
(25, 'Charlie and the Chocolate Factory', 8, 10, 1964, 6, 5),
(26, 'Matilda', 8, 10, 1988, 6, 3),
(27, 'The Cat in the Hat', 8, 9, 1957, 5, 4),
(28, 'It', 12, 1, 1986, 4, 2),
(29, 'The Shining', 12, 3, 1977, 4, 1),
(30, 'Bird Box', 12, 5, 2014, 3, 2),
(31, 'Batman: Year One', 13, 7, 1987, 5, 3),
(32, 'Spider-Man: Blue', 13, 2, 2002, 4, 2),
(33, 'Watchmen', 13, 6, 1986, 6, 4),
(34, 'The Divine Comedy', 14, 9, 1320, 5, 3),
(35, 'Leaves of Grass', 14, 1, 1855, 5, 2),
(36, 'Milk and Honey', 14, 10, 2014, 4, 3),
(37, 'The Bible', 15, 4, 1611, 8, 6),
(38, 'The Quran', 15, 3, 632, 7, 5),
(39, 'The Bhagavad Gita', 15, 5, 2000, 5, 4),
(40, 'The Power of Now', 15, 8, 1997, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `book_copies`
--

CREATE TABLE `book_copies` (
  `copy_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `shelf_location` varchar(20) DEFAULT NULL,
  `acquire_date` date DEFAULT NULL,
  `status` enum('available','borrowed','reserved','lost','damaged') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_copies`
--

INSERT INTO `book_copies` (`copy_id`, `book_id`, `shelf_location`, `acquire_date`, `status`) VALUES
(1, 1, 'A1', NULL, 'available'),
(2, 1, 'A2', NULL, 'borrowed'),
(3, 2, 'B1', NULL, 'available'),
(4, 2, 'B2', NULL, 'available'),
(5, 3, 'C1', NULL, 'borrowed'),
(6, 3, 'C2', NULL, 'available'),
(7, 4, 'D1', NULL, 'available'),
(8, 5, 'E1', NULL, 'reserved'),
(9, 6, 'F1', NULL, 'available'),
(10, 6, 'F2', NULL, 'available'),
(11, 7, 'G1', NULL, 'borrowed'),
(12, 8, 'H1', NULL, 'available'),
(13, 8, 'H2', NULL, 'available'),
(14, 9, 'I1', NULL, 'available'),
(15, 10, 'J1', NULL, 'borrowed'),
(16, 11, 'K1', NULL, 'available'),
(17, 12, 'L1', NULL, 'available'),
(18, 13, 'M1', NULL, 'available'),
(19, 14, 'N1', NULL, 'borrowed'),
(20, 15, 'O1', NULL, 'available'),
(21, 16, 'P1', NULL, 'reserved'),
(22, 17, 'Q1', NULL, 'available'),
(23, 18, 'R1', NULL, 'available'),
(24, 19, 'S1', NULL, 'borrowed'),
(25, 20, 'T1', NULL, 'available'),
(26, 21, 'U1', NULL, 'available'),
(27, 22, 'V1', NULL, 'borrowed'),
(28, 23, 'W1', NULL, 'available'),
(29, 24, 'X1', NULL, 'available'),
(30, 25, 'Y1', NULL, 'reserved'),
(31, 26, 'Z1', NULL, 'available'),
(32, 27, 'AA1', NULL, 'available'),
(33, 28, 'AB1', NULL, 'borrowed'),
(34, 29, 'AC1', NULL, 'available'),
(35, 30, 'AD1', NULL, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `book_tags`
--

CREATE TABLE `book_tags` (
  `book_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_tags`
--

INSERT INTO `book_tags` (`book_id`, `tag_id`, `tag_name`) VALUES
(1, 1, '#romance #classic'),
(2, 1, '#romance #drama'),
(3, 1, '#romance #drama'),
(4, 4, '#fantasy #adventure'),
(5, 4, '#fantasy #adventure'),
(6, 4, '#fantasy #adventure'),
(7, 6, '#mystery #detective'),
(8, 8, '#thriller #mystery'),
(9, 8, '#thriller #mystery'),
(10, 9, '#science #astronomy'),
(11, 9, '#science #astronomy'),
(12, 9, '#science #physics'),
(13, 12, '#programming #technology'),
(14, 12, '#programming #technology'),
(15, 13, '#technology #networking'),
(16, 15, '#history #nonfiction'),
(17, 15, '#history #nonfiction'),
(18, 15, '#history #nonfiction'),
(19, 15, '#history #biography'),
(20, 15, '#history #biography'),
(21, 17, '#biography #technology'),
(22, 18, '#philosophy #classic'),
(23, 18, '#philosophy #classic'),
(24, 18, '#philosophy #classic'),
(25, 19, '#children #fantasy'),
(26, 19, '#children #fantasy'),
(27, 19, '#children #fantasy'),
(28, 20, '#horror #thriller'),
(29, 20, '#horror #thriller'),
(30, 8, '#thriller #mystery'),
(31, 21, '#comic #superhero'),
(32, 21, '#comic #superhero'),
(33, 21, '#comic #superhero'),
(34, 23, '#poetry #classic'),
(35, 23, '#poetry #classic'),
(36, 24, '#poetry #modern'),
(37, 25, '#religion #spirituality'),
(38, 25, '#religion #spirituality'),
(39, 25, '#religion #spirituality'),
(40, 27, '#selfhelp #spirituality');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Fiction'),
(2, 'Non-Fiction'),
(3, 'Science'),
(4, 'Technology'),
(5, 'History'),
(6, 'Biography'),
(7, 'Philosophy'),
(8, 'Children'),
(9, 'Fantasy'),
(10, 'Mystery'),
(11, 'Romance'),
(12, 'Horror'),
(13, 'Comics'),
(14, 'Poetry'),
(15, 'Religion');

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `fine_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `fine_amount` decimal(8,2) NOT NULL,
  `is_paid` enum('yes','no') DEFAULT NULL,
  `issued_date` date NOT NULL,
  `paid_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fines`
--

INSERT INTO `fines` (`fine_id`, `loan_id`, `fine_amount`, `is_paid`, `issued_date`, `paid_date`) VALUES
(1, 2, 5.00, 'yes', '2025-12-05', '2025-12-06'),
(2, 4, 3.50, 'yes', '2025-12-15', '2025-12-16'),
(3, 7, 7.25, 'no', '2025-12-13', NULL),
(4, 9, 2.00, 'yes', '2025-12-11', '2025-12-12'),
(5, 1, 4.00, 'no', '2025-12-16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `copy_id` int(11) NOT NULL,
  `borrowed_date` date NOT NULL,
  `due_date` date NOT NULL,
  `returned_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `member_id`, `copy_id`, `borrowed_date`, `due_date`, `returned_date`) VALUES
(1, 1, 2, '2025-12-01', '2025-12-15', NULL),
(2, 2, 5, '2025-11-20', '2025-12-04', '2025-12-03'),
(3, 3, 7, '2025-11-25', '2025-12-09', NULL),
(4, 4, 10, '2025-11-30', '2025-12-14', '2025-12-12'),
(5, 5, 15, '2025-12-03', '2025-12-17', NULL),
(6, 6, 18, '2025-12-05', '2025-12-19', NULL),
(7, 7, 22, '2025-11-28', '2025-12-12', '2025-12-10'),
(8, 8, 25, '2025-12-02', '2025-12-16', NULL),
(9, 9, 28, '2025-11-26', '2025-12-10', '2025-12-08'),
(10, 10, 30, '2025-12-06', '2025-12-20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(20) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(25) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `membership_date` date DEFAULT NULL,
  `member_status` enum('active','expired','blocked') DEFAULT 'active',
  `type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `user_id`, `full_name`, `gender`, `phone`, `email`, `address`, `date_of_birth`, `membership_date`, `member_status`, `type_id`) VALUES
(1, 3, 'Amina Rahman', 'Female', '01711000001', 'amina@mail.com', 'Dhanmondi, Dhaka', '2002-05-12', '2024-01-10', 'active', 1),
(2, 4, 'Hasib Khan', 'Male', '01711000002', 'hasib@mail.com', 'Uttara, Dhaka', '2000-09-22', '2024-02-14', 'active', 2),
(3, 5, 'Nabila Sultana', 'Female', '01711000003', 'nabila@mail.com', 'Mirpur, Dhaka', '2001-02-10', '2024-03-01', 'active', 1),
(4, 8, 'Mehjabin Noor', 'Female', '01711000008', 'mehjabin@mail.com', 'Rampura, Dhaka', '2002-07-18', '2024-03-14', 'active', 1),
(5, 9, 'Fahim Islam', 'Male', '01711000009', 'fahim@mail.com', 'Bashundhara, Dhaka', '1999-01-10', '2024-02-19', 'active', 2),
(6, 10, 'Tania Akter', 'Female', '01711000010', 'tania@mail.com', 'Gulshan, Dhaka', '2000-04-22', '2024-03-05', 'expired', 1),
(7, 12, 'Sadia Karim', 'Female', '01711000012', 'sadia@mail.com', 'Keraniganj', '2001-10-03', '2024-04-02', 'active', 3),
(8, 13, 'Imran Hossain', 'Male', '01711000013', 'imran@mail.com', 'Jatrabari, Dhaka', '1996-09-12', '2024-01-08', 'active', 2),
(9, 15, 'Nazmul Hasan', 'Male', '01711000015', 'nazmul@mail.com', 'Rajshahi', '1997-03-05', '2024-01-14', 'active', 2),
(10, 16, 'Khadija Tuhin', 'Female', '01711000016', 'khadija@mail.com', 'Khulna', '2002-02-11', '2024-02-28', 'expired', 3),
(11, 17, 'Rashid Uddin', 'Male', '01711000017', 'rashid@mail.com', 'Barishal', '1999-05-23', '2024-01-11', 'active', 1),
(12, 19, 'Jahidul Islam', 'Male', '01711000019', 'jahid@mail.com', 'Cumilla', '1998-10-12', '2024-01-03', 'blocked', 1),
(13, 20, 'Sumaiya Haque', 'Female', '01711000020', 'sumaiya@mail.com', 'Mymensingh', '2001-09-14', '2024-03-16', 'active', 2);

-- --------------------------------------------------------

--
-- Table structure for table `membership_types`
--

CREATE TABLE `membership_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(20) NOT NULL,
  `duration_months` int(11) NOT NULL,
  `annual_fee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_types`
--

INSERT INTO `membership_types` (`type_id`, `type_name`, `duration_months`, `annual_fee`) VALUES
(1, 'Regular', 12, 500.00),
(2, 'Premium', 12, 1200.00),
(3, 'Student', 12, 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `publisher_id` int(11) NOT NULL,
  `publisher_name` varchar(50) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`publisher_id`, `publisher_name`, `address`, `phone`) VALUES
(1, 'Pearson Education', '221B Baker Street, London', '+44-20-1234-5678'),
(2, 'McGraw-Hill', '1221 Avenue of the Americas, New York', '+1-212-512-2000'),
(3, 'Oxford University Press', 'Great Clarendon Street, Oxford', '+44-1865-556767'),
(4, 'Cambridge University Press', 'University Printing House, Cambridge', '+44-1223-312393'),
(5, 'Wiley', '111 River Street, Hoboken, New Jersey', '+1-201-748-6000'),
(6, 'Rokomari Publications', 'Farmgate, Dhaka', '+880-1711-000001'),
(7, 'Anondo Publishers', 'Banglabazar, Dhaka', '+880-1711-000002'),
(8, 'Scholastic', '557 Broadway, New York', '+1-212-343-6100'),
(9, 'Penguin Random House', '1745 Broadway, New York', '+1-212-782-9000'),
(10, 'HarperCollins', '195 Broadway, New York', '+1-212-207-7000');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `reserved_date` date DEFAULT NULL,
  `status` enum('pending','fulfilled','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`reservation_id`, `member_id`, `book_id`, `reserved_date`, `status`) VALUES
(101, 1, 7, '2024-01-12', 'fulfilled'),
(102, 2, 12, '2024-02-05', 'pending'),
(103, 3, 1, '2024-02-28', 'cancelled'),
(104, 4, 16, '2024-03-10', 'fulfilled'),
(105, 5, 25, '2024-03-17', 'pending'),
(106, 6, 4, '2024-03-22', 'fulfilled'),
(107, 7, 9, '2024-04-01', 'pending'),
(108, 8, 18, '2024-04-14', 'cancelled'),
(109, 9, 21, '2024-04-20', 'fulfilled'),
(110, 10, 3, '2024-04-25', 'pending'),
(111, 11, 6, '2024-05-02', 'fulfilled'),
(112, 12, 13, '2024-05-10', 'cancelled'),
(113, 13, 8, '2024-05-15', 'pending'),
(114, 1, 23, '2024-05-20', 'fulfilled'),
(115, 3, 10, '2024-05-28', 'pending'),
(116, 5, 14, '2024-06-03', 'fulfilled'),
(117, 7, 5, '2024-06-08', 'cancelled'),
(118, 9, 2, '2024-06-15', 'pending'),
(119, 11, 11, '2024-06-20', 'fulfilled'),
(120, 13, 19, '2024-06-24', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `staff_name` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(25) DEFAULT NULL,
  `position` varchar(20) DEFAULT NULL,
  `hire_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `user_id`, `staff_name`, `phone`, `email`, `position`, `hire_date`) VALUES
(1, 1, 'Aminul Hasan', '01710000001', 'aminul@lib.com', 'Admin', '2020-01-15'),
(2, 2, 'Shamima Akter', '01710000002', 'shamima@lib.com', 'Librarian', '2021-03-12'),
(3, 6, 'Fahim Rahman', '01710000003', 'fahim@lib.com', 'Assistant', '2022-06-05'),
(4, 7, 'Tania Khatun', '01710000004', 'tania@lib.com', 'Librarian', '2019-11-19'),
(5, 11, 'Rajib Khan', '01710000005', 'rajib@lib.com', 'Clerk', '2023-02-01'),
(6, 14, 'Mahmudul Islam', '01710000006', 'mahmud@lib.com', 'Librarian', '2020-07-11'),
(7, 18, 'Nasrin Jahan', '01710000007', 'nasrin@lib.com', 'Assistant', '2021-09-30'),
(8, 3, 'Saad Chowdhury', '01710000008', 'saad@lib.com', 'Clerk', '2022-05-22'),
(9, 4, 'Nabila Haque', '01710000009', 'nabila@lib.com', 'Librarian', '2018-12-10'),
(10, 5, 'Yusuf Karim', '01710000010', 'yusuf@lib.com', 'Admin', '2019-03-18'),
(11, 8, 'Shamim Uddin', '01710000011', 'shamim@lib.com', 'Assistant', '2023-01-05'),
(12, 9, 'Rafiq Hossain', '01710000012', 'rafiq@lib.com', 'Cleaner', '2021-10-09'),
(13, 10, 'Sadia Noor', '01710000013', 'sadia@lib.com', 'Receptionist', '2022-01-15'),
(14, 12, 'Mizanur Rahman', '01710000014', 'mizan@lib.com', 'Security', '2020-11-01'),
(15, 13, 'Shimu Akter', '01710000015', 'shimu@lib.com', 'Assistant', '2023-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `role` enum('admin','librarian','member') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin01', 'pass123', 'admin', '2025-12-09 18:23:25'),
(2, 'librarian1', 'lib123', 'librarian', '2025-12-09 18:23:25'),
(3, 'member01', 'm12345', 'member', '2025-12-09 18:23:25'),
(4, 'member02', 'm12345', 'member', '2025-12-09 18:23:25'),
(5, 'member03', 'm12345', 'member', '2025-12-09 18:23:25'),
(6, 'librarian2', 'lib234', 'librarian', '2025-12-09 18:23:25'),
(7, 'admin02', 'pass456', 'admin', '2025-12-09 18:23:25'),
(8, 'member04', 'm54321', 'member', '2025-12-09 18:23:25'),
(9, 'member05', 'm54321', 'member', '2025-12-09 18:23:25'),
(10, 'member06', 'm54321', 'member', '2025-12-09 18:23:25'),
(11, 'librarian3', 'lib789', 'librarian', '2025-12-09 18:23:25'),
(12, 'member07', 'abcd12', 'member', '2025-12-09 18:23:25'),
(13, 'member08', 'abcd12', 'member', '2025-12-09 18:23:25'),
(14, 'admin03', 'admin12', 'admin', '2025-12-09 18:23:25'),
(15, 'member09', 'xyz123', 'member', '2025-12-09 18:23:25'),
(16, 'member10', 'xyz123', 'member', '2025-12-09 18:23:25'),
(17, 'member11', 'xyz123', 'member', '2025-12-09 18:23:25'),
(18, 'librarian4', 'lib000', 'librarian', '2025-12-09 18:23:25'),
(19, 'member12', 'pass111', 'member', '2025-12-09 18:23:25'),
(20, 'member13', 'pass222', 'member', '2025-12-09 18:23:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Indexes for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD PRIMARY KEY (`copy_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `book_tags`
--
ALTER TABLE `book_tags`
  ADD PRIMARY KEY (`book_id`,`tag_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`fine_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `copy_id` (`copy_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `membership_types`
--
ALTER TABLE `membership_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`publisher_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_copies`
--
ALTER TABLE `book_copies`
  MODIFY `copy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `fine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`publisher_id`);

--
-- Constraints for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD CONSTRAINT `book_copies_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fines_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`loan_id`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`copy_id`) REFERENCES `book_copies` (`copy_id`);

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `members_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `membership_types` (`type_id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
