<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../includes/header.php";
include "../../includes/navbar.php";
require_once "../../models/Book.php";

$bookModel = new Book();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: /DoAnTHWeb/index.php");
    exit();
}

// Lấy dữ liệu sách
$stmt = $bookModel->getById($id);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "Không tìm thấy sách!";
    exit();
}

// Kiểm tra xem sách đã được thêm vào kho chưa
require_once "../../models/Library.php";
$library = new Library();
$isInLibrary = false;
if (isset($_SESSION["user_id"])) {
    $isInLibrary = $library->isInLibrary($_SESSION["user_id"], $book['id']);
}
?>

<style>
body {
    background: #f5f5f5;
    font-family: Arial, sans-serif;
}

.book-detail-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.book-detail-wrapper {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    gap: 40px;
}

.book-image-section {
    position: relative;
    flex-shrink: 0;
}

.back-button {
    position: absolute;
    top: -50px;
    left: 0;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    z-index: 10;
    transition: background 0.3s;
}

.back-button:hover {
    background: rgba(0,0,0,0.8);
}

.book-image-wrapper {
    width: 350px;
    height: 500px;
    overflow: hidden;
    border-radius: 10px;
    background: #f0f0f0;
}

.book-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-info-section {
    flex: 1;
}

.book-title {
    font-size: 32px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
}

.book-info-item {
    margin-bottom: 15px;
    font-size: 16px;
}

.book-info-label {
    font-weight: bold;
    color: #666;
    display: inline-block;
    width: 120px;
}

.book-info-value {
    color: #333;
}

.book-price {
    font-size: 28px;
    color: #e60000;
    font-weight: bold;
    margin: 20px 0;
}

.book-quantity {
    font-size: 18px;
    color: #333;
    margin: 15px 0;
}

.book-quantity .quantity-value {
    font-weight: bold;
    color: #28a745;
}

.book-buttons {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.btn-borrow {
    background: #28a745;
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s;
}

.btn-borrow:hover {
    background: #218838;
}

.btn-add-to-library {
    background: #e9ecef;
    color: #333;
    padding: 15px 40px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s;
}

.btn-add-to-library:hover {
    background: #dee2e6;
}

/* Modal Styles */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1000; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.5); 
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; 
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    position: relative;
    animation: animatetop 0.4s;
}

@keyframes animatetop {
    from {top: -300px; opacity: 0}
    to {top: 0; opacity: 1}
}

.close-modal {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close-modal:hover,
.close-modal:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

.quantity-input-group {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin: 15px 0;
}

.quantity-btn {
    width: 30px;
    height: 30px;
    background: #eee;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
}

.quantity-btn:hover {
    background: #e2e6ea;
}

.quantity-input {
    width: 60px;
    height: 30px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Hide arrow for number input */
.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.btn-confirm-add {
    background: #28a745;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-confirm-add:hover {
    background: #218838;
}
</style>

<div class="book-detail-container">
    <div class="book-detail-wrapper">
        <div class="book-image-section">
            <a href="/DoAnTHWeb/index.php" class="back-button">← Trở về</a>
            <div class="book-image-wrapper">
                <?php if (!empty($book['image'])): ?>
                    <img src="../../assets/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/350x500/cccccc/666666?text=No+Image" alt="No image">
                <?php endif; ?>
            </div>
        </div>

        <div class="book-info-section">
            <h1 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h1>

            <div class="book-info-item">
                <span class="book-info-label">Tác giả:</span>
                <span class="book-info-value"><?php echo htmlspecialchars($book['author'] ?? 'Chưa có thông tin'); ?></span>
            </div>

            <div class="book-info-item">
                <span class="book-info-label">Thể loại:</span>
                <span class="book-info-value"><?php echo htmlspecialchars($book['category'] ?? 'Chưa có thông tin'); ?></span>
            </div>

            <div class="book-info-item">
                <span class="book-info-label">Năm xuất bản:</span>
                <span class="book-info-value"><?php echo $book['publish_year'] ?? 'Chưa có thông tin'; ?></span>
            </div>

            <div class="book-price">
                <?php echo number_format($book['price'], 0, ',', '.'); ?> đ
            </div>

            <div class="book-quantity">
                Số lượng còn lại: <span class="quantity-value"><?php echo isset($book['quantity']) ? $book['quantity'] : 0; ?></span>
            </div>

    <div class="book-buttons">
                <!-- Nút Mượn: Mở modal mượn ngay -->
                <button onclick="openQuickBorrowModal()" class="btn-borrow">Mượn</button>
                
                <?php if (isset($_SESSION["user_id"])): ?>
                    <!-- Nút thêm vào kho: Mở modal số lượng -->
                    <button onclick="openAddToLibraryModal()" class="btn-add-to-library">Thêm vào kho sách</button>
                <?php else: ?>
                    <a href="/DoAnTHWeb/views/auth/login.php" class="btn-add-to-library">Thêm vào kho sách</a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                <div style="margin-top: 20px; padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
                    ✓ Đã thêm sách vào kho sách thành công!
                </div>
            <?php endif; ?>

            <!-- Add to Library Modal -->
            <div id="addToLibraryModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeAddToLibraryModal()">&times;</span>
                    <div class="modal-header">Thêm vào kho sách</div>
                    <form action="../../controllers/LibraryController.php?action=add" method="POST">
                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                        
                        <p style="text-align: center;">Nhập số lượng:</p>
                        <div class="quantity-input-group">
                            <button type="button" class="quantity-btn" onclick="decreaseAddQty()">-</button>
                            <input type="number" name="quantity" id="addQtyInput" class="quantity-input" value="1" min="1" max="<?php echo $book['quantity']; ?>">
                            <button type="button" class="quantity-btn" onclick="increaseAddQty()">+</button>
                        </div>
                        
                        <button type="submit" class="btn-confirm-add" style="width:100%; margin-top:10px;">Xác nhận thêm</button>
                    </form>
                </div>
            </div>

            <!-- Quick Borrow Modal -->
            <div id="quickBorrowModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeQuickBorrowModal()">&times;</span>
                    <div class="modal-header">Mượn sách</div>
                    <!-- Note: Sử dụng action borrow giống như trong kho sách, nhưng đây là mượn trực tiếp 1 cuốn -->
                    <!-- Để đơn giản, ta có thể route nó qua action borrow giống logic cũ hoặc quick_borrow nếu muốn tách biệt. -->
                    <!-- Nhưng user yêu cầu logic giống nhau: "nút mượn nữa khi ấn vào củng hiện form nhập số lượng và sau đó chọn thời gian... ấn xác nhận là xong" -->
                    <!-- Ta sẽ dùng form submit đến borrowBooks nhưng cần cấu trúc data giống với việc select từ library -->
                    <form action="../../controllers/LibraryController.php?action=borrow" method="POST">
                        <input type="hidden" name="selected_books[]" value="<?php echo $book['id']; ?>">
                        
                        <p style="text-align: center;">Số lượng:</p>
                        <div class="quantity-input-group">
                            <button type="button" class="quantity-btn" onclick="decreaseBorrowQty()">-</button>
                            <input type="number" name="quantities[<?php echo $book['id']; ?>]" id="borrowQtyInput" class="quantity-input" value="1" min="1" max="<?php echo $book['quantity']; ?>">
                            <button type="button" class="quantity-btn" onclick="increaseBorrowQty()">+</button>
                        </div>
                        
                        <p style="margin-top: 15px;">Chọn thời gian mượn:</p>
                        <select name="borrow_duration" style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc;">
                            <option value="3">3 ngày</option>
                            <option value="7" selected>7 ngày</option>
                            <option value="14">14 ngày</option>
                        </select>

                        <button type="submit" class="btn-confirm-add" style="width:100%; margin-top:20px;">Xác nhận mượn</button>
                    </form>
                </div>
            </div>

            <script>
            function openAddToLibraryModal() {
                document.getElementById('addToLibraryModal').style.display = 'block';
            }
            function closeAddToLibraryModal() {
                document.getElementById('addToLibraryModal').style.display = 'none';
            }
            
            function openQuickBorrowModal() {
                document.getElementById('quickBorrowModal').style.display = 'block';
            }
            function closeQuickBorrowModal() {
                document.getElementById('quickBorrowModal').style.display = 'none';
            }

            function increaseAddQty() {
                var input = document.getElementById('addQtyInput');
                var max = parseInt(input.getAttribute('max'));
                var val = parseInt(input.value);
                if (val < max) input.value = val + 1;
            }
            function decreaseAddQty() {
                var input = document.getElementById('addQtyInput');
                var val = parseInt(input.value);
                if (val > 1) input.value = val - 1;
            }

             function increaseBorrowQty() {
                var input = document.getElementById('borrowQtyInput');
                var max = parseInt(input.getAttribute('max'));
                var val = parseInt(input.value);
                if (val < max) input.value = val + 1;
            }
            function decreaseBorrowQty() {
                var input = document.getElementById('borrowQtyInput');
                var val = parseInt(input.value);
                if (val > 1) input.value = val - 1;
            }

            window.onclick = function(event) {
                var m1 = document.getElementById('addToLibraryModal');
                var m2 = document.getElementById('quickBorrowModal');
                if (event.target == m1) m1.style.display = "none";
                if (event.target == m2) m2.style.display = "none";
            }
            </script>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>

