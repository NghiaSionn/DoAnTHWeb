<?php
require_once "config/database.php";
require_once "models/Library.php";
require_once "models/Book.php";
require_once "models/BorrowRecord.php";

echo "<h1>Starting Comprehensive Borrow Verification...</h1>";

$library = new Library();
$bookModel = new Book();
$borrowRecord = new BorrowRecord();
$db = new Database();
$conn = $db->connect();

// Mock Data
$userId = 999; 
$bookId = 1; 

// Setup: Ensure book has stock
$conn->exec("UPDATE books SET quantity = 100 WHERE id = $bookId");

// Reset User Data
$conn->exec("DELETE FROM user_library WHERE user_id = $userId");
$conn->exec("DELETE FROM borrow_records WHERE user_id = $userId");


echo "<h3>Phase 1: Add to Warehouse</h3>";
// Add 5 copies
$library->addToLibrary($userId, $bookId, 5);
$userLib = $library->getUserLibrary($userId);
$found = false;
foreach($userLib as $item) {
    if ($item['id'] == $bookId && $item['user_quantity'] == 5) $found = true;
}
if ($found) echo "<p style='color:green'>✓ Added 5 copies to warehouse</p>";
else echo "<p style='color:red'>✗ Failed to add to warehouse</p>";


echo "<h3>Phase 2: Remove Multiple from Warehouse</h3>";
$library->addToLibrary($userId, $bookId, 2); // Add more to be safe (now 7)
$library->removeMultipleFromLibrary($userId, [$bookId]);
$userLib = $library->getUserLibrary($userId);
$found = false;
foreach($userLib as $item) {
    if ($item['id'] == $bookId) $found = true;
}
if (!$found) echo "<p style='color:green'>✓ Removed book from warehouse</p>";
else echo "<p style='color:red'>✗ Failed to remove from warehouse</p>";


echo "<h3>Phase 3: Borrowing Logic (Warehouse Flow)</h3>";
// Setup again
$library->addToLibrary($userId, $bookId, 10);
$bookModel->decreaseStock($bookId, 0); // Reset stock logic not needed, we update directly
$conn->exec("UPDATE books SET quantity = 100 WHERE id = $bookId");

// Borrow 3
$qtyToBorrow = 3;
$duration = 7;

// Logic inside Controller
$library->decreaseQuantity($userId, $bookId, $qtyToBorrow);
$bookModel->decreaseStock($bookId, $qtyToBorrow);
$borrowRecord->create($userId, $bookId, $qtyToBorrow, $duration);

// Verify
$stmt = $conn->query("SELECT quantity FROM books WHERE id = $bookId");
$postStock = $stmt->fetchColumn();

$userLib = $library->getUserLibrary($userId);
$postLibQty = 0;
foreach($userLib as $item) {
    if ($item['id'] == $bookId) $postLibQty = $item['user_quantity'];
}

$records = $borrowRecord->getByUser($userId);
$hasRecord = false;
foreach($records as $rec) {
    if ($rec['book_id'] == $bookId && $rec['quantity'] == $qtyToBorrow) $hasRecord = true;
}

if ($postStock == 97) echo "<p style='color:green'>✓ Global Stock Decreased correctly (100 -> 97)</p>";
else echo "<p style='color:red'>✗ Global Stock Incorrect: $postStock</p>";

if ($postLibQty == 7) echo "<p style='color:green'>✓ Warehouse Quantity Decreased correctly (10 -> 7)</p>";
else echo "<p style='color:red'>✗ Warehouse Quantity Incorrect: $postLibQty</p>";

if ($hasRecord) echo "<p style='color:green'>✓ Borrow Record Created</p>";
else echo "<p style='color:red'>✗ No Borrow Record Found</p>";

echo "<h1>Verification Completed</h1>";
?>
