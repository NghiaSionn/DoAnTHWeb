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
require_once "../../models/BorrowRecord.php";

$borrowRecordModel = new BorrowRecord();
$records = $borrowRecordModel->getByUser($_SESSION["user_id"]);
?>

<div class="history-container">
    <h2 class="history-title">Lịch sử mượn sách</h2>

    <?php if (count($records) > 0): ?>
        <div class="history-table-wrapper">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Sách</th>
                        <th>Ngày mượn</th>
                        <th>Hạn trả</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td class="book-cell">
                                <?php if (!empty($record['image'])): ?>
                                    <img src="../../assets/uploads/<?php echo htmlspecialchars($record['image']); ?>" alt="Book">
                                <?php else: ?>
                                    <div style="width: 50px; height: 70px; background: #eee;"></div>
                                <?php endif; ?>
                                <div>
                                    <div class="book-title"><?php echo htmlspecialchars($record['title']); ?></div>
                                    <div class="book-author"><?php echo htmlspecialchars($record['author'] ?? ''); ?></div>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($record['borrow_date'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($record['return_date_due'])); ?></td>
                            <td align="center">
                                <?php echo isset($record['quantity']) ? $record['quantity'] : 1; ?>
                            </td>
                            <td>
                                <?php 
                                    $statusClass = '';
                                    $statusText = '';
                                    
                                    switch($record['status']) {
                                        case 'borrowed':
                                            $statusClass = 'status-borrowed';
                                            $statusText = 'Đang mượn';
                                            break;
                                        case 'returned':
                                            $statusClass = 'status-returned';
                                            $statusText = 'Đã trả';
                                            break;
                                        case 'overdue':
                                            $statusClass = 'status-overdue';
                                            $statusText = 'Quá hạn';
                                            break;
                                        default:
                                            $statusText = $record['status'];
                                    }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <?php echo $statusText; ?>
                                </span>
                                
                                <?php if ($record['status'] == 'borrowed'): ?>
                                    <form action="../../controllers/LibraryController.php?action=return" method="POST" style="display:inline-block; margin-left: 10px;">
                                        <input type="hidden" name="record_id" value="<?php echo $record['id']; ?>">
                                        <button type="submit" class="btn-return" onclick="return confirm('Bạn có chắc muốn trả cuốn sách này?')">Trả sách</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-history">
            Bạn chưa mượn cuốn sách nào.
        </div>
    <?php endif; ?>
</div>

<style>
.history-container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}

.history-title {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.history-table-wrapper {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
}

.history-table th {
    background: #333;
    color: white;
    padding: 15px;
    text-align: left;
}

.history-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.book-cell {
    display: flex;
    align-items: center;
    gap: 15px;
}

.book-cell img {
    width: 50px;
    height: 70px;
    object-fit: cover;
    border-radius: 4px;
}

.book-title {
    font-weight: bold;
    color: #333;
}

.book-author {
    font-size: 13px;
    color: #666;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.status-borrowed {
    background: #e7f3ff;
    color: #0066cc;
}

.status-returned {
    background: #d4edda;
    color: #155724;
}

.status-overdue {
    background: #f8d7da;
    color: #721c24;
}

.btn-return {
    background: #6c757d;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    font-size: 12px;
    cursor: pointer;
    transition: background 0.2s;
}

.btn-return:hover {
    background: #5a6268;
}

.empty-history {
    text-align: center;
    padding: 50px;
    color: #999;
    font-size: 16px;
    background: white;
    border-radius: 10px;
}
</style>

<?php include "../../includes/footer.php"; ?>