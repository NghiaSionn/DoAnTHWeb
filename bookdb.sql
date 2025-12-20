-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 07, 2025 lúc 03:22 PM
-- Phiên bản máy phục vụ: 8.4.7
-- Phiên bản PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bookdb`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `publish_year` int DEFAULT NULL,

  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(100) NOT NULL,
  `quantity` int NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publish_year`, `image`, `created_at`, `category`, `quantity`) VALUES
(1, 'Lập trình PHP & MySQL', 'Nhà xuất bản Khoa Học và Kỹ Thuật', 2024, '1764783599_Lập trình PHP & MySQL.png', '2025-12-03 14:33:02', 'Lập Trình', 20),
(2, 'Số Đỏ', 'Vũ Trọng Phụng', 1936, '1764954954_s_-b1.webp', '2025-12-05 16:40:19', 'Văn học Việt Nam', 10),
(3, 'Dế Mèn Phiêu Lưu Ký', 'Tô Hoài', 1941, '1764955121_de_men_phieu_luu_ky_ban_du__to_hoai.jpg', '2025-12-05 16:43:25', 'Văn học Việt Nam', 10),
(4, 'Đắc Nhân Tâm', 'Dale Carnegie', 1936, '1764955183_-1726814978.jpg', '2025-12-05 16:44:18', 'Kỹ năng sống (Self-help)', 10),
(5, 'Nhà Giả Kim', 'Paulo Coelho', 1988, '1764955248_f9c0e62731cffb2c7855d828529dc5c3.webp', '2025-12-05 16:51:10', 'Kỹ năng sống (Self-help)', 10),
(6, 'Vũ Trụ (Cosmos)', 'Carl Sagan', 1980, '1764955371_images.jpg', '2025-12-05 16:55:43', 'Khoa học - Vũ trụ', 9),
(7, 'Lược Sử Thời Gian', 'Stephen Hawking', 1988, '1764955454_nxbtre_full_20002022_040049.jpg', '2025-12-05 16:58:02', 'Khoa học - Vũ trụ', 11),
(8, 'Dạy Con Làm Giàu (Tập 1)', 'Robert T. Kiyosaki', 1997, '1764955476_58948399.jpg', '2025-12-05 16:59:05', 'Kinh tế - Tài chính', 9),
(9, 'Nhà Đầu Tư Thông Minh', 'Benjamin Graham', 1949, '1764955498_15572-nha-dau-tu-thong-minh-1.jpg', '2025-12-05 16:59:53', 'Kinh tế - Tài chính', 10),
(10, 'Thám Tử Lừng Danh Conan (Tập 1)', 'Gosho Aoyama', 1994, '1764955525_6fa35df18462ddacc6502a51bea2f8b6.jpg', '2025-12-05 17:00:28', 'Truyện tranh (Manga)', 10),
(11, 'One Piece (Tập 1)', 'Eiichiro Oda', 1997, '1764955543_image_63818.webp', '2025-12-05 17:03:55', 'Truyện tranh (Manga)', 10),
(12, '100 bài tập C++ có lời giải', 'Lưu Trường Hải Lân', 2020, '1764955689_UYEfVBswSa.png', '2025-12-05 17:28:09', 'Lập Trình', 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrow_records`
--

DROP TABLE IF EXISTS `borrow_records`;
CREATE TABLE IF NOT EXISTS `borrow_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date_due` date NOT NULL,
  `return_date_actual` date DEFAULT NULL,
  `status` enum('borrowed','returned','overdue','lost') NOT NULL DEFAULT 'borrowed',
  `quantity` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `borrow_records`
--

INSERT INTO `borrow_records` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date_due`, `return_date_actual`, `status`, `quantity`) VALUES
(1, 1, 2, '2025-12-01', '2025-12-15', NULL, 'borrowed', 1),
(2, 1, 4, '2025-11-20', '2025-12-04', '2025-12-03', 'returned', 1),
(3, 2, 7, '2025-12-05', '2025-12-19', '2025-12-07', 'returned', 1),
(4, 2, 1, '2025-12-07', '2025-12-14', '2025-12-07', 'returned', 1),
(5, 1, 8, '2025-12-07', '2025-12-21', NULL, 'borrowed', 1),
(6, 1, 6, '2025-12-07', '2025-12-21', NULL, 'borrowed', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'nghiasion', '$2y$10$kYYlCd3kf9yUvVAS7goPaOS./zO4OINdY0h.M11qqNfKYwG9ZrbZW', 'nghiasion@gmail.com', 'user'),
(2, 'admin1', '$2y$10$XqtEfOniusViI338UZITPufnkYdV1YMmklKWVIUcshMrHGIoE//DS', 'admin@library.com', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_library`
--

DROP TABLE IF EXISTS `user_library`;
CREATE TABLE IF NOT EXISTS `user_library` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `quantity` int DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_book` (`user_id`,`book_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Foreign Keys for tables
--

ALTER TABLE `borrow_records`
  ADD CONSTRAINT `fk_borrow_records_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_borrow_records_books` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_library`
  ADD CONSTRAINT `fk_user_library_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_library_books` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
