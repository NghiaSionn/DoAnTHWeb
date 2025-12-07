-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th12 07, 2025 lúc 12:19 PM (Đã cập nhật)
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
-- Cấu trúc bảng cho bảng `books` (ĐÃ CẬP NHẬT: THÊM CỘT 'quantity')
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
  `quantity` int NOT NULL DEFAULT 10, -- Cột mới: Số lượng sách tồn kho, mặc định là 10
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `books` (ĐÃ THÊM GIÁ TRỊ MẶC ĐỊNH CHO 'quantity')
--

INSERT INTO `books` (`id`, `title`, `author`, `publish_year`, `price`, `image`, `created_at`, `category`, `quantity`) VALUES
(1, 'Lập trình PHP & MySQL', 'Nhà xuất bản Khoa Học và Kỹ Thuật', 2024, 150000.00, '1764783599_Lập trình PHP & MySQL.png', '2025-12-03 14:33:02', 'Lập Trình', 10),
(2, 'Số Đỏ', 'Vũ Trọng Phụng', 1936, 63000.00, '1764954954_s_-b1.webp', '2025-12-05 16:40:19', 'Văn học Việt Nam', 10),
(3, 'Dế Mèn Phiêu Lưu Ký', 'Tô Hoài', 1941, 50000.00, '1764955121_de_men_phieu_luu_ky_ban_du__to_hoai.jpg', '2025-12-05 16:43:25', 'Văn học Việt Nam', 10),
(4, 'Đắc Nhân Tâm', 'Dale Carnegie', 1936, 86000.00, '1764955183_-1726814978.jpg', '2025-12-05 16:44:18', 'Kỹ năng sống (Self-help)', 10),
(5, 'Nhà Giả Kim', 'Paulo Coelho', 1988, 79000.00, '1764955248_f9c0e62731cffb2c7855d828529dc5c3.webp', '2025-12-05 16:51:10', 'Kỹ năng sống (Self-help)', 10),
(6, 'Vũ Trụ (Cosmos)', 'Carl Sagan', 1980, 179000.00, '1764955371_images.jpg', '2025-12-05 16:55:43', 'Khoa học - Vũ trụ', 10),
(7, 'Lược Sử Thời Gian', 'Stephen Hawking', 1988, 115000.00, '1764955454_nxbtre_full_20002022_040049.jpg', '2025-12-05 16:58:02', 'Khoa học - Vũ trụ', 10),
(8, 'Dạy Con Làm Giàu (Tập 1)', 'Robert T. Kiyosaki', 1997, 80000.00, '1764955476_58948399.jpg', '2025-12-05 16:59:05', 'Kinh tế - Tài chính', 10),
(9, 'Nhà Đầu Tư Thông Minh', 'Benjamin Graham', 1949, 199000.00, '1764955498_15572-nha-dau-tu-thong-minh-1.jpg', '2025-12-05 16:59:53', 'Kinh tế - Tài chính', 10),
(10, 'Thám Tử Lừng Danh Conan (Tập 1)', 'Gosho Aoyama', 1994, 35000.00, '1764955525_6fa35df18462ddacc6502a51bea2f8b6.jpg', '2025-12-05 17:00:28', 'Truyện tranh (Manga)', 10),
(11, 'One Piece (Tập 1)', 'Eiichiro Oda', 1997, 25000.00, '1764955543_image_63818.webp', '2025-12-05 17:03:55', 'Truyện tranh (Manga)', 10),
(12, '100 bài tập C++ có lời giải', 'Lưu Trường Hải Lân', 2020, 250000.00, '1764955689_UYEfVBswSa.png', '2025-12-05 17:28:09', 'Lập Trình', 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users` (ĐÃ CẬP NHẬT: THÊM CỘT 'email')
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) DEFAULT NULL, -- Cột mới: Email của người dùng
  `role` enum('admin','user') DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`) -- Thêm UNIQUE KEY cho email để đảm bảo duy nhất
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users` (ĐÃ THÊM DỮ LIỆU EMAIL)
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'nghiasion', '$2y$10$kYYlCd3kf9yUvVAS7goPaOS./zO4OINdY0h.M11qqNfKYwG9ZrbZW', 'nghiasion@gmail.com', 'user'),
(2, 'admin1', '$2y$10$XqtEfOniusViI338UZITPufnkYdV1YMmklKWVIUcshMrHGIoE//DS', 'admin@library.com', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `borrow_records` (BẢNG MỚI: Quản lý Mượn/Trả)
--

DROP TABLE IF EXISTS `borrow_records`;
CREATE TABLE IF NOT EXISTS `borrow_records` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `borrow_date` DATE NOT NULL,
  `return_date_due` DATE NOT NULL, -- Ngày dự kiến phải trả
  `return_date_actual` DATE NULL DEFAULT NULL, -- Ngày trả thực tế (NULL nếu đang mượn)
  `status` ENUM('borrowed','returned','overdue','lost') NOT NULL DEFAULT 'borrowed',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE, -- Liên kết với bảng users
  FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE -- Liên kết với bảng books
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `borrow_records` (DỮ LIỆU THỬ NGHIỆM)
--

INSERT INTO `borrow_records` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date_due`, `return_date_actual`, `status`) VALUES
(1, 1, 2, '2025-12-01', '2025-12-15', NULL, 'borrowed'), -- nghiasion mượn Số Đỏ
(2, 1, 4, '2025-11-20', '2025-12-04', '2025-12-03', 'returned'), -- nghiasion đã trả Đắc Nhân Tâm
(3, 2, 7, '2025-12-05', '2025-12-19', NULL, 'borrowed'); -- admin1 mượn Lược Sử Thời Gian

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;