<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../models/Library.php";
require_once "../models/Book.php";

class LibraryController {

    public function addToLibrary() {
        if (!isset($_SESSION["user_id"])) {
            header("Location: /DoAnTHWeb/views/auth/login.php");
            exit();
        }

        $bookId = $_GET['book_id'] ?? null;
        if (!$bookId) {
            header("Location: /DoAnTHWeb/index.php");
            exit();
        }

        $library = new Library();
        $library->addToLibrary($_SESSION["user_id"], $bookId);

        header("Location: /DoAnTHWeb/views/books/detail.php?id=" . $bookId . "&added=1");
        exit();
    }

    public function removeFromLibrary() {
        if (!isset($_SESSION["user_id"])) {
            header("Location: /DoAnTHWeb/views/auth/login.php");
            exit();
        }

        $bookId = $_GET['book_id'] ?? null;
        if (!$bookId) {
            header("Location: /DoAnTHWeb/views/books/library.php");
            exit();
        }

        $library = new Library();
        $library->removeFromLibrary($_SESSION["user_id"], $bookId);

        header("Location: /DoAnTHWeb/views/books/library.php?removed=1");
        exit();
    }

    public function borrowBooks() {
        if (!isset($_SESSION["user_id"])) {
            header("Location: /DoAnTHWeb/views/auth/login.php");
            exit();
        }

        $selectedBooks = $_POST['selected_books'] ?? [];
        if (empty($selectedBooks)) {
            header("Location: /DoAnTHWeb/views/books/library.php?error=no_selection");
            exit();
        }

        $borrowDuration = $_POST['borrow_duration'] ?? 7; // Mặc định 7 ngày
        
        // Xử lý mượn sách - có thể lưu vào bảng borrow_records
        // Ở đây tôi sẽ chỉ hiển thị thông báo thành công
        // Bạn có thể mở rộng để lưu vào database với thời gian mượn = $borrowDuration
        
        header("Location: /DoAnTHWeb/views/books/library.php?borrowed=1&duration=" . $borrowDuration);
        exit();
    }
}

$action = $_GET["action"] ?? "";
$controller = new LibraryController();

if ($action == "add") {
    $controller->addToLibrary();
} elseif ($action == "remove") {
    $controller->removeFromLibrary();
} elseif ($action == "borrow" && $_SERVER["REQUEST_METHOD"] == "POST") {
    $controller->borrowBooks();
}

