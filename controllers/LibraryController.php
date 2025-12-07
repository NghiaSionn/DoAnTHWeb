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

        $bookId = $_POST['book_id'] ?? $_GET['book_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!$bookId) {
            header("Location: /DoAnTHWeb/index.php");
            exit();
        }

        $library = new Library();
        $library->addToLibrary($_SESSION["user_id"], $bookId, $quantity);

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

    public function removeMultiple() {
        if (!isset($_SESSION["user_id"])) {
            header("Location: /DoAnTHWeb/views/auth/login.php");
            exit();
        }

        $bookIds = $_POST['selected_books'] ?? [];
        
        if (!empty($bookIds)) {
            $library = new Library();
            $library->removeMultipleFromLibrary($_SESSION["user_id"], $bookIds);
        }

        header("Location: /DoAnTHWeb/views/books/library.php?removed_multiple=1");
        exit();
    }

    public function borrowBooks() {
        if (!isset($_SESSION["user_id"])) {
            header("Location: /DoAnTHWeb/views/auth/login.php");
            exit();
        }

        $selectedBooks = $_POST['selected_books'] ?? [];
        $quantities = $_POST['quantities'] ?? []; // Array [book_id => quantity]

        if (empty($selectedBooks)) {
            header("Location: /DoAnTHWeb/views/books/library.php?error=no_selection");
            exit();
        }

        $borrowDuration = $_POST['borrow_duration'] ?? 7; 
        
        require_once "../models/BorrowRecord.php";
        $library = new Library();
        $bookModel = new Book();
        $borrowRecord = new BorrowRecord();
        $userId = $_SESSION["user_id"];

        $successCount = 0;

        foreach ($selectedBooks as $bookId) {
            // Lấy số lượng muốn mượn (mặc định 1)
            $qtyToBorrow = isset($quantities[$bookId]) ? (int)$quantities[$bookId] : 1;
            if ($qtyToBorrow <= 0) $qtyToBorrow = 1;

            // 1. Giảm số lượng trong kho cá nhân (User Library)
            $library->decreaseQuantity($userId, $bookId, $qtyToBorrow);

            // 2. Giảm số lượng tồn kho chung (Books table)
            $bookModel->decreaseStock($bookId, $qtyToBorrow);

            // 3. Tạo bản ghi mượn
            $borrowRecord->create($userId, $bookId, $qtyToBorrow, $borrowDuration);
            
            $successCount++;
        }
        
        header("Location: /DoAnTHWeb/views/books/history.php?borrowed=1&count=" . $successCount);
        exit();
    }
}

$action = $_GET["action"] ?? "";
$controller = new LibraryController();

if ($action == "add") {
    $controller->addToLibrary();
} elseif ($action == "remove") {
    $controller->removeFromLibrary();
} elseif ($action == "remove_multiple" && $_SERVER["REQUEST_METHOD"] == "POST") {
    $controller->removeMultiple();
} elseif ($action == "borrow" && $_SERVER["REQUEST_METHOD"] == "POST") {
    $controller->borrowBooks();
}

