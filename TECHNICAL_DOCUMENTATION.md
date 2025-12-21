# TÀI LIỆU KỸ THUẬT - DỰ ÁN QUẢN LÝ THƯ VIỆN

## 📚 TỔNG QUAN DỰ ÁN

Đây là hệ thống quản lý thư viện trực tuyến cho phép:
- Quản lý sách (thêm, sửa, xóa)
- Người dùng có thể thêm sách vào kho cá nhân
- Mượn sách với số lượng và thời hạn
- Theo dõi lịch sử mượn trả

---

## 🏗️ KIẾN TRÚC HỆ THỐNG

### Mô hình MVC (Model-View-Controller)

```
DoAnTHWeb/
├── models/          → Xử lý dữ liệu, tương tác database
├── views/           → Giao diện người dùng (HTML/PHP)
├── controllers/     → Xử lý logic nghiệp vụ
├── config/          → Cấu hình database
├── assets/          → CSS, hình ảnh, uploads
└── includes/        → Header, footer, navbar
```

**Lợi ích của MVC:**
- Tách biệt logic và giao diện → dễ bảo trì
- Tái sử dụng code
- Nhiều người có thể làm việc song song

---

## 🔐 BẢO MẬT MẬT KHẨU

### 1. Hàm `password_hash()`
**Vị trí:** `models/User.php` (dòng 17)

```php
$hashed = password_hash($password, PASSWORD_BCRYPT);
```

**Cách hoạt động:**
- Sử dụng thuật toán **Bcrypt** (một-chiều, không thể giải mã ngược)
- Tự động tạo **salt** ngẫu nhiên cho mỗi mật khẩu
- Mỗi lần hash cùng 1 mật khẩu → kết quả khác nhau

**Ví dụ:**
```
Input:  "matkhau123"
Output: "$2y$10$Qj6vZ8x.../abc123..." (60 ký tự)
```

### 2. Hàm `password_verify()`
**Vị trí:** `controllers/AuthController.php` (dòng 32)

```php
if ($data && password_verify($password, $data["password"])) {
    // Đăng nhập thành công
}
```

**Cách hoạt động:**
- So sánh mật khẩu người dùng nhập với hash trong database
- Tự động nhận diện salt và thuật toán từ chuỗi hash
- Trả về `true` nếu khớp, `false` nếu sai

---

## 💾 QUẢN LÝ DATABASE

### Kết nối Database
**File:** `config/database.php`

```php
class Database {
    private $host = "localhost";
    private $db_name = "bookdb";
    private $username = "root";
    private $password = "";
    
    public function connect() {
        return new PDO("mysql:host=$host;dbname=$db_name", 
                       $username, $password);
    }
}
```

**Sử dụng PDO (PHP Data Objects):**
- ✅ Bảo vệ khỏi SQL Injection
- ✅ Hỗ trợ nhiều loại database
- ✅ Prepared Statements

### Prepared Statements
**Ví dụ từ `models/User.php`:**

```php
$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $this->conn->prepare($sql);
$stmt->bindParam(":username", $username);
$stmt->execute();
```

**Tại sao an toàn?**
- Tách biệt SQL và dữ liệu
- Database tự động escape ký tự đặc biệt
- Ngăn chặn SQL Injection

---

## 📊 LOGIC NGHIỆP VỤ QUAN TRỌNG

### 1. Hệ Thống Kho Sách Cá Nhân (Library)

**File:** `models/Library.php`

#### a) Thêm sách vào kho với số lượng

```php
public function addToLibrary($userId, $bookId, $quantity = 1) {
    $sql = "INSERT INTO user_library (user_id, book_id, quantity) 
            VALUES (:user_id, :book_id, :quantity)
            ON DUPLICATE KEY UPDATE 
            quantity = quantity + :quantity";
}
```

**Logic:**
- Nếu sách **chưa có** trong kho → Thêm mới
- Nếu sách **đã có** → Cộng thêm số lượng
- Sử dụng `ON DUPLICATE KEY UPDATE` (tính năng MySQL)

**Ví dụ:**
```
Lần 1: addToLibrary(userId=1, bookId=5, quantity=3)
       → user_library: {user_id: 1, book_id: 5, quantity: 3}

Lần 2: addToLibrary(userId=1, bookId=5, quantity=2)
       → user_library: {user_id: 1, book_id: 5, quantity: 5}
```

#### b) Giảm số lượng khi mượn

```php
public function decreaseQuantity($userId, $bookId, $amount) {
    // Bước 1: Giảm số lượng
    $sql = "UPDATE user_library 
            SET quantity = quantity - :amount 
            WHERE user_id = :user_id AND book_id = :book_id";
    
    // Bước 2: Xóa nếu quantity <= 0
    $checkSql = "DELETE FROM user_library 
                 WHERE user_id = :user_id 
                 AND book_id = :book_id 
                 AND quantity <= 0";
}
```

**Logic:**
- Trừ số lượng trong kho cá nhân
- Nếu về 0 → Tự động xóa khỏi kho

---

### 2. Hệ Thống Mượn Sách (Borrow Records)

**File:** `models/BorrowRecord.php`

#### Tạo bản ghi mượn sách

```php
public function create($userId, $bookId, $quantity, $durationDays) {
    $returnDateDue = date('Y-m-d', strtotime("+$durationDays days"));
    
    $sql = "INSERT INTO borrow_records 
            (user_id, book_id, quantity, borrow_date, return_date_due, status) 
            VALUES (:user_id, :book_id, :quantity, CURRENT_DATE, 
                    :return_date_due, 'borrowed')";
}
```

**Logic tính ngày trả:**
```php
// Ví dụ: Mượn 7 ngày
$durationDays = 7;
$returnDateDue = date('Y-m-d', strtotime("+7 days"));

// Hôm nay: 2025-12-20
// Ngày trả: 2025-12-27
```

**Trạng thái (Status):**
- `borrowed` → Đang mượn
- `returned` → Đã trả
- `overdue` → Quá hạn

---

### 3. Quy Trình Mượn Sách Hoàn Chỉnh

**Khi user mượn sách, hệ thống thực hiện 3 bước:**

```php
// Bước 1: Giảm số lượng trong kho cá nhân
$library->decreaseQuantity($userId, $bookId, $qtyToBorrow);

// Bước 2: Giảm tồn kho tổng (global stock)
$bookModel->decreaseStock($bookId, $qtyToBorrow);

// Bước 3: Tạo bản ghi mượn
$borrowRecord->create($userId, $bookId, $qtyToBorrow, $duration);
```

**Ví dụ cụ thể:**
```
User ID: 1 mượn 3 cuốn sách ID: 5 trong 7 ngày

TRƯỚC KHI MƯỢN:
- Kho cá nhân (user_library): 10 cuốn
- Kho tổng (books.quantity): 100 cuốn

SAU KHI MƯỢN:
- Kho cá nhân: 10 - 3 = 7 cuốn
- Kho tổng: 100 - 3 = 97 cuốn
- Bản ghi mượn: {user_id: 1, book_id: 5, quantity: 3, 
                 borrow_date: 2025-12-20, 
                 return_date_due: 2025-12-27, 
                 status: 'borrowed'}
```

---

## 🔑 KHÓA NGOẠI (FOREIGN KEY)

### Ràng buộc quan hệ giữa các bảng

```sql
-- Bảng user_library
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
```

**Ý nghĩa:**
- `user_id` phải tồn tại trong bảng `users`
- `book_id` phải tồn tại trong bảng `books`
- `ON DELETE CASCADE`: Khi xóa user → tự động xóa dữ liệu liên quan

**Lỗi thường gặp:**
```
SQLSTATE[23000]: Integrity constraint violation: 1452
Cannot add or update a child row: a foreign key constraint fails
```

**Nguyên nhân:** Cố gắng thêm `user_id = 999` nhưng không có user nào có ID = 999.

---

## 📤 UPLOAD FILE

**File:** `controllers/BookController.php`

```php
if (!empty($_FILES['image']['name'])) {
    // Tạo tên file unique bằng timestamp
    $imageName = time() . "_" . $_FILES['image']['name'];
    
    // Di chuyển file từ thư mục tạm vào uploads
    move_uploaded_file($_FILES['image']['tmp_name'], 
                      "../assets/uploads/" . $imageName);
}
```

**Logic:**
1. Kiểm tra có file upload không
2. Tạo tên file unique: `1734723456_book_cover.jpg`
3. Lưu vào `assets/uploads/`
4. Lưu tên file vào database

**Tại sao dùng `time()`?**
- Tránh trùng tên file
- `time()` trả về số giây từ 1970 → luôn unique

---

## 🎨 SESSION & AUTHENTICATION

**File:** `controllers/AuthController.php`

```php
// Sau khi đăng nhập thành công
$_SESSION["user_id"] = $data["id"];
$_SESSION["username"] = $data["username"];
$_SESSION["role"] = $data["role"];
```

**Kiểm tra đăng nhập:**
```php
if (isset($_SESSION["user_id"])) {
    // User đã đăng nhập
} else {
    // Chưa đăng nhập → redirect về login
}
```

**Phân quyền:**
```php
if ($_SESSION["role"] == "admin") {
    // Hiển thị trang quản trị
} else {
    // Hiển thị trang user thường
}
```

---

## 🔄 MIGRATION & AUTO-UPDATE

**File:** `models/Library.php`

```php
private function checkAndAddQuantityColumn() {
    // Kiểm tra xem cột 'quantity' đã tồn tại chưa
    $checkSql = "SHOW COLUMNS FROM user_library LIKE 'quantity'";
    $stmt = $this->conn->prepare($checkSql);
    $stmt->execute();
    
    // Nếu chưa có → Thêm cột
    if ($stmt->rowCount() == 0) {
        $alterSql = "ALTER TABLE user_library 
                     ADD COLUMN quantity INT DEFAULT 1 AFTER book_id";
        $this->conn->exec($alterSql);
    }
}
```

**Tại sao cần?**
- Database cũ không có cột `quantity`
- Tự động cập nhật cấu trúc bảng khi chạy code mới
- Không cần chạy SQL thủ công

---

## 📝 SQL QUERIES QUAN TRỌNG

### 1. JOIN để lấy thông tin đầy đủ

```php
// Lấy danh sách mượn kèm thông tin sách
$sql = "SELECT br.*, b.title, b.image, b.author 
        FROM borrow_records br
        JOIN books b ON br.book_id = b.id
        WHERE br.user_id = :user_id
        ORDER BY br.borrow_date DESC";
```

**Giải thích:**
- `br.*` → Tất cả cột từ bảng `borrow_records`
- `b.title, b.image, b.author` → Thông tin sách từ bảng `books`
- `JOIN ... ON` → Ghép 2 bảng theo điều kiện `book_id = id`

### 2. Xóa nhiều bản ghi cùng lúc

```php
public function removeMultipleFromLibrary($userId, $bookIds) {
    // Tạo placeholders: ?, ?, ?
    $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
    
    $sql = "DELETE FROM user_library 
            WHERE user_id = ? AND book_id IN ($placeholders)";
    
    // Merge params: [userId, bookId1, bookId2, bookId3]
    $params = array_merge([$userId], $bookIds);
    $stmt->execute($params);
}
```

**Ví dụ:**
```php
removeMultipleFromLibrary(1, [5, 7, 9]);

// SQL thực tế:
// DELETE FROM user_library 
// WHERE user_id = 1 AND book_id IN (5, 7, 9)
```

---

## 🧮 CÁC CÔNG THỨC & TÍNH TOÁN

### 1. Tính ngày trả sách
```php
$durationDays = 7;
$returnDateDue = date('Y-m-d', strtotime("+$durationDays days"));
```

### 2. Tính tổng số lượng
```php
// Cộng dồn số lượng
quantity = quantity + :quantity
```

### 3. Kiểm tra tồn tại
```php
SELECT COUNT(*) as count FROM user_library 
WHERE user_id = :user_id AND book_id = :book_id

// Nếu count > 0 → Đã tồn tại
```

---

## 🛡️ BẢO MẬT & BEST PRACTICES

### 1. Chống SQL Injection
✅ **ĐÚNG:**
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $userId);
```

❌ **SAI:**
```php
$sql = "SELECT * FROM users WHERE id = $userId"; // Nguy hiểm!
```

### 2. Chống XSS (Cross-Site Scripting)
```php
echo htmlspecialchars($book['title']); // Escape HTML
```

### 3. Validate dữ liệu
```php
$quantity = $_POST['quantity'] ?? 0; // Giá trị mặc định
```

---

## 📂 CẤU TRÚC DATABASE

### Bảng `users`
```sql
- id (INT, PRIMARY KEY)
- username (VARCHAR)
- password (VARCHAR) → Hash bằng bcrypt
- role (ENUM: 'user', 'admin')
```

### Bảng `books`
```sql
- id (INT, PRIMARY KEY)
- title (VARCHAR)
- author (VARCHAR)
- category (VARCHAR)
- publish_year (INT)
- quantity (INT) → Tồn kho tổng
- image (VARCHAR)
```

### Bảng `user_library`
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- book_id (INT, FOREIGN KEY)
- quantity (INT) → Số lượng trong kho cá nhân
- added_at (TIMESTAMP)
```

### Bảng `borrow_records`
```sql
- id (INT, PRIMARY KEY)
- user_id (INT, FOREIGN KEY)
- book_id (INT, FOREIGN KEY)
- quantity (INT)
- borrow_date (DATE)
- return_date_due (DATE)
- return_date_actual (DATE)
- status (ENUM: 'borrowed', 'returned', 'overdue')
```

---

## 🔍 DEBUG & TESTING

### File kiểm thử: `verify_full_system.php`

**Mục đích:** Kiểm tra toàn bộ quy trình mượn sách

**Các bước test:**
1. Thêm sách vào kho cá nhân
2. Xóa sách khỏi kho
3. Mượn sách và kiểm tra:
   - Kho cá nhân giảm đúng
   - Kho tổng giảm đúng
   - Bản ghi mượn được tạo

---

## 💡 KIẾN THỨC NỀN TẢNG

### 1. OOP (Object-Oriented Programming)
```php
class Book {
    private $conn; // Thuộc tính
    
    public function create() { // Phương thức
        // Logic
    }
}
```

### 2. MVC Pattern
- **Model:** Xử lý dữ liệu
- **View:** Hiển thị giao diện
- **Controller:** Điều khiển logic

### 3. PDO vs MySQLi
- PDO: Hỗ trợ nhiều database (MySQL, PostgreSQL, SQLite)
- MySQLi: Chỉ MySQL

### 4. Prepared Statements
- Tách SQL và dữ liệu
- Bảo mật cao
- Tái sử dụng query

---

## 📚 TÀI LIỆU THAM KHẢO

- [PHP Password Hashing](https://www.php.net/manual/en/function.password-hash.php)
- [PDO Tutorial](https://www.php.net/manual/en/book.pdo.php)
- [SQL JOIN](https://www.w3schools.com/sql/sql_join.asp)
- [MVC Pattern](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)

---

**Tài liệu này được tạo để giúp hiểu rõ logic và kiến thức trong dự án Quản Lý Thư Viện.**
