<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: /DoAnTHWeb/index.php");
    exit();
}

include "../../includes/header.php";
include "../../includes/navbar.php";
require_once "../../models/BorrowRecord.php";

$borrowRecordModel = new BorrowRecord();
$records = $borrowRecordModel->getAllRecords();
?>

<div class="admin-container">
    <h2 class="admin-title">Quản lý Mượn Trả Sách</h2>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Người mượn</th>
                    <th>Sách</th>
                    <th>Ngày mượn</th>
                    <th>Hạn trả</th>
                    <th>SL</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($records) > 0): ?>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-name"><?php echo htmlspecialchars($record['username']); ?></div>
                                    <div class="user-email"><?php echo htmlspecialchars($record['email']); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="book-info">
                                    <div class="book-title"><?php echo htmlspecialchars($record['title']); ?></div>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($record['borrow_date'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($record['return_date_due'])); ?></td>
                            <td align="center"><?php echo $record['quantity']; ?></td>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;">Chưa có dữ liệu mượn trả nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.admin-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.admin-title {
    margin-bottom: 25px;
    color: #333;
    font-size: 24px;
    border-left: 5px solid #007bff;
    padding-left: 15px;
}

.table-wrapper {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th {
    background: #f8f9fa;
    color: #495057;
    font-weight: bold;
    padding: 15px;
    text-align: left;
    border-bottom: 2px solid #dee2e6;
}

.admin-table td {
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.admin-table tr:last-child td {
    border-bottom: none;
}

.user-info .user-name {
    font-weight: bold;
    color: #333;
}

.user-info .user-email {
    font-size: 13px;
    color: #666;
}

.book-info .book-title {
    font-weight: 500;
    color: #007bff;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
    white-space: nowrap;
}

.status-borrowed {
    background: #cce5ff;
    color: #004085;
}

.status-returned {
    background: #d4edda;
    color: #155724;
}

.status-overdue {
    background: #f8d7da;
    color: #721c24;
}
</style>

<?php include "../../includes/footer.php"; ?>
