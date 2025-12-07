<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: /DoAnTHWeb/views/auth/login.php");
    exit();
}

include "../../includes/header.php";
include "../../includes/navbar.php";
require_once "../../models/Library.php";

$library = new Library();
$userBooks = $library->getUserLibrary($_SESSION["user_id"]);
?>

<style>
body {
    background: #f5f5f5;
    font-family: Arial, sans-serif;
}

.library-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.library-header {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.library-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.btn-borrow-selected {
    background: #28a745;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    display: none;
}

.btn-borrow-selected.show {
    display: inline-block;
}

.btn-borrow-selected:hover {
    background: #218838;
}

.library-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.library-table table {
    width: 100%;
    border-collapse: collapse;
}

.library-table th {
    background: #333;
    color: white;
    padding: 15px;
    text-align: left;
    font-weight: bold;
}

.library-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.library-table tr:hover {
    background: #f8f9fa;
}

.book-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.book-image-cell {
    width: 80px;
}

.book-image-cell img {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

.book-info-cell {
    min-width: 200px;
}

.book-title {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

.book-category {
    color: #666;
    font-size: 14px;
}

.book-quantity {
    text-align: center;
    font-weight: bold;
    color: #28a745;
}

.empty-library {
    text-align: center;
    padding: 60px 20px;
    color: #999;
    font-size: 18px;
}

/* Modal xác nhận mượn sách */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 30px;
    border-radius: 10px;
    width: 400px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.modal-header {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

.modal-body {
    margin-bottom: 20px;
    color: #666;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-confirm {
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
}

.btn-confirm:hover {
    background: #218838;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-cancel:hover {
    background: #5a6268;
}

.duration-select-wrapper {
    margin-top: 20px;
    position: relative;
}

.duration-select-btn {
    width: 100%;
    padding: 12px 15px;
    background: white;
    border: 1px solid #ced4da;
    border-radius: 5px;
    cursor: pointer;
    text-align: left;
    font-size: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: border-color 0.2s;
}

.duration-select-btn:hover {
    border-color: #28a745;
}

.duration-select-btn::after {
    content: '▼';
    font-size: 12px;
    color: #666;
}

.duration-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ced4da;
    border-radius: 5px;
    margin-top: 5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
    overflow: hidden;
}

.duration-dropdown.show {
    display: block;
}

.duration-option {
    padding: 12px 15px;
    cursor: pointer;
    transition: background 0.2s;
    border-bottom: 1px solid #f0f0f0;
}

.duration-option:last-child {
    border-bottom: none;
}

.duration-option:hover {
    background: #f8f9fa;
}

.duration-option.selected {
    background: #e7f3ff;
    color: #0066cc;
    font-weight: bold;
}
</style>

<div class="library-container">
    <div class="library-header">
        <h2 class="library-title">Kho sách của tôi</h2>
        <button class="btn-borrow-selected" id="btnBorrowSelected">Mượn sách</button>
    </div>

    <?php if (count($userBooks) > 0): ?>
        <div class="library-table">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" id="selectAll" class="book-checkbox">
                        </th>
                        <th>Ảnh</th>
                        <th>Tên sách</th>
                        <th>Thể loại</th>
                        <th style="text-align: center;">Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    <form id="borrowForm" action="../../controllers/LibraryController.php?action=borrow" method="POST">
                        <input type="hidden" name="borrow_duration" id="borrowDurationInput" value="3">
                        <?php foreach ($userBooks as $book): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_books[]" value="<?php echo $book['id']; ?>" class="book-checkbox book-select">
                                </td>
                                <td class="book-image-cell">
                                    <?php if (!empty($book['image'])): ?>
                                        <img src="../../assets/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/60x80/cccccc/666666?text=No+Image" alt="No image">
                                    <?php endif; ?>
                                </td>
                                <td class="book-info-cell">
                                    <div class="book-title"><?php echo htmlspecialchars($book['title']); ?></div>
                                    <div style="color: #999; font-size: 13px;"><?php echo htmlspecialchars($book['author'] ?? 'Chưa có'); ?></div>
                                </td>
                                <td>
                                    <div class="book-category"><?php echo htmlspecialchars($book['category']); ?></div>
                                </td>
                                <td class="book-quantity">
                                    <?php echo isset($book['quantity']) ? $book['quantity'] : 0; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </form>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-library">
            📚 Kho sách của bạn đang trống. Hãy thêm sách vào kho sách!
        </div>
    <?php endif; ?>
</div>

<!-- Modal xác nhận mượn sách -->
<div id="borrowModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Xác nhận mượn sách</div>
        <div class="modal-body">
            <p>Bạn có chắc chắn muốn mượn các sách đã chọn không?</p>
            
            <div class="duration-select-wrapper">
                <div class="duration-select-btn" id="durationSelectBtn" onclick="toggleDurationDropdown()">
                    <span id="durationSelectText">3 ngày</span>
                </div>
                <div class="duration-dropdown" id="durationDropdown">
                    <div class="duration-option selected" data-value="3" onclick="selectDuration(3, '3 ngày', this)">3 ngày</div>
                    <div class="duration-option" data-value="7" onclick="selectDuration(7, '7 ngày', this)">7 ngày</div>
                    <div class="duration-option" data-value="14" onclick="selectDuration(14, '14 ngày', this)">14 ngày</div>
                </div>
            </div>
            <input type="hidden" name="borrow_duration" id="borrowDurationHidden" value="3">
        </div>
        <div class="modal-buttons">
            <button type="button" class="btn-cancel" onclick="closeModal()">Hủy</button>
            <button type="button" class="btn-confirm" onclick="confirmBorrow()">Xác nhận mượn sách</button>
        </div>
    </div>
</div>

<script>
// Chọn tất cả
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.book-select');
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateBorrowButton();
});

// Cập nhật nút mượn sách
document.querySelectorAll('.book-select').forEach(checkbox => {
    checkbox.addEventListener('change', updateBorrowButton);
});

function updateBorrowButton() {
    const selected = document.querySelectorAll('.book-select:checked');
    const btn = document.getElementById('btnBorrowSelected');
    if (selected.length > 0) {
        btn.classList.add('show');
        btn.textContent = `Mượn sách (${selected.length})`;
    } else {
        btn.classList.remove('show');
    }
}

// Hiển thị modal khi click nút mượn sách
document.getElementById('btnBorrowSelected').addEventListener('click', function() {
    const selected = document.querySelectorAll('.book-select:checked');
    if (selected.length > 0) {
        document.getElementById('borrowModal').style.display = 'block';
    }
});

function closeModal() {
    document.getElementById('borrowModal').style.display = 'none';
}

function toggleDurationDropdown() {
    const dropdown = document.getElementById('durationDropdown');
    dropdown.classList.toggle('show');
}

function selectDuration(value, text, element) {
    // Cập nhật text hiển thị
    document.getElementById('durationSelectText').textContent = text;
    document.getElementById('borrowDurationHidden').value = value;
    
    // Cập nhật selected class
    document.querySelectorAll('.duration-option').forEach(option => {
        option.classList.remove('selected');
    });
    if (element) {
        element.classList.add('selected');
    }
    
    // Đóng dropdown
    document.getElementById('durationDropdown').classList.remove('show');
}

function confirmBorrow() {
    // Lấy giá trị thời gian mượn đã chọn
    const selectedDuration = document.getElementById('borrowDurationHidden').value;
    document.getElementById('borrowDurationInput').value = selectedDuration;
    document.getElementById('borrowForm').submit();
}

// Đóng modal khi click bên ngoài
window.onclick = function(event) {
    const modal = document.getElementById('borrowModal');
    if (event.target == modal) {
        closeModal();
    }
    
    // Đóng dropdown khi click bên ngoài
    const dropdown = document.getElementById('durationDropdown');
    const selectBtn = document.getElementById('durationSelectBtn');
    if (dropdown && !dropdown.contains(event.target) && !selectBtn.contains(event.target)) {
        dropdown.classList.remove('show');
    }
}
</script>

<?php include "../../includes/footer.php"; ?>

