-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 06, 2025 lúc 12:19 PM
-- Phiên bản máy phục vụ: 9.1.0
-- Phiên bản PHP: 8.3.14

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
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publish_year`, `price`, `image`, `created_at`, `category`) VALUES
(1, 'Lập trình PHP & MySQL', 'Nhà xuất bản Khoa Học và Kỹ Thuật', 2024, 150000.00, '1764783599_Lập trình PHP & MySQL.png', '2025-12-03 14:33:02', 'Lập Trình'),
(2, 'Số Đỏ', 'Vũ Trọng Phụng', 1936, 63000.00, '1764954954_s_-b1.webp', '2025-12-05 16:40:19', 'Văn học Việt Nam'),
(3, 'Dế Mèn Phiêu Lưu Ký', 'Tô Hoài', 1941, 50000.00, '1764955121_de_men_phieu_luu_ky_ban_du__to_hoai.jpg', '2025-12-05 16:43:25', 'Văn học Việt Nam'),
(4, 'Đắc Nhân Tâm', 'Dale Carnegie', 1936, 86000.00, '1764955183_-1726814978.jpg', '2025-12-05 16:44:18', 'Kỹ năng sống (Self-help)'),
(5, 'Nhà Giả Kim', 'Paulo Coelho', 1988, 79000.00, '1764955248_f9c0e62731cffb2c7855d828529dc5c3.webp', '2025-12-05 16:51:10', 'Kỹ năng sống (Self-help)'),
(6, 'Vũ Trụ (Cosmos)', 'Carl Sagan', 1980, 179000.00, '1764955371_images.jpg', '2025-12-05 16:55:43', 'Khoa học - Vũ trụ'),
(7, 'Lược Sử Thời Gian', 'Stephen Hawking', 1988, 115000.00, '1764955454_nxbtre_full_20002022_040049.jpg', '2025-12-05 16:58:02', 'Khoa học - Vũ trụ'),
(8, 'Dạy Con Làm Giàu (Tập 1)', 'Robert T. Kiyosaki', 1997, 80000.00, '1764955476_58948399.jpg', '2025-12-05 16:59:05', 'Kinh tế - Tài chính'),
(9, 'Nhà Đầu Tư Thông Minh', 'Benjamin Graham', 1949, 199000.00, '1764955498_15572-nha-dau-tu-thong-minh-1.jpg', '2025-12-05 16:59:53', 'Kinh tế - Tài chính'),
(10, 'Thám Tử Lừng Danh Conan (Tập 1)', 'Gosho Aoyama', 1994, 35000.00, '1764955525_6fa35df18462ddacc6502a51bea2f8b6.jpg', '2025-12-05 17:00:28', 'Truyện tranh (Manga)'),
(11, 'One Piece (Tập 1)', 'Eiichiro Oda', 1997, 25000.00, '1764955543_image_63818.webp', '2025-12-05 17:03:55', 'Truyện tranh (Manga)'),
(12, '100 bài tập C++ có lời giải', 'Lưu Trường Hải Lân', 2020, 250000.00, '1764955689_UYEfVBswSa.png', '2025-12-05 17:28:09', 'Lập Trình');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'nghiasion', '$2y$10$kYYlCd3kf9yUvVAS7goPaOS./zO4OINdY0h.M11qqNfKYwG9ZrbZW', 'user'),
(2, 'admin1', '$2y$10$XqtEfOniusViI338UZITPufnkYdV1YMmklKWVIUcshMrHGIoE//DS', 'admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
