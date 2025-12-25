<?php

/* ===============================
   THIẾT LẬP ĐƯỜNG DẪN DATABASE
================================ */
$dbDir  = __DIR__ . '/db';
$dbFile = $dbDir . '/database.sqlite';

/* ===============================
   TẠO THƯ MỤC DB
================================ */
if (!is_dir($dbDir)) {
    if (!mkdir($dbDir, 0777, true)) {
        die("❌ Không thể tạo thư mục db. Vui lòng kiểm tra quyền ghi.");
    }
    echo "📁 Đã tạo thư mục db<br>";
} else {
    echo "ℹ️ Thư mục db đã tồn tại<br>";
}

/* ===============================
   TẠO FILE SQLITE
================================ */
if (!file_exists($dbFile)) {
    $fp = fopen($dbFile, 'w');
    if (!$fp) {
        die("❌ Không thể tạo file database.sqlite");
    }
    fclose($fp);
    echo "✅ Đã tạo file database.sqlite<br>";
} else {
    echo "ℹ️ File database.sqlite đã tồn tại<br>";
}

/* ===============================
   KẾT NỐI SQLITE
================================ */
try {
    $db = new PDO("sqlite:" . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Kết nối SQLite thành công<br>";
} catch (PDOException $e) {
    die("❌ Lỗi kết nối DB: " . $e->getMessage());
}

/* ===============================
   BẢNG USERS (CÓ PHÂN QUYỀN)
================================ */
$db->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT DEFAULT 'user'
)
");
echo "✅ Bảng users đã sẵn sàng<br>";

/* ===============================
   BẢNG OCR HISTORY
================================ */
$db->exec("
CREATE TABLE IF NOT EXISTS ocr_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    image_path TEXT,
    result TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)
");
echo "✅ Bảng ocr_history đã sẵn sàng<br>";

/* ===============================
   TẠO ADMIN MẶC ĐỊNH
================================ */
$checkAdmin = $db
    ->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")
    ->fetchColumn();

if ($checkAdmin == 0) {
    $adminPass = password_hash("admin123", PASSWORD_DEFAULT);

    $stmt = $db->prepare("
        INSERT INTO users (username, password, role)
        VALUES (?, ?, 'admin')
    ");
    $stmt->execute(["admin", $adminPass]);

    echo "👑 Admin mặc định đã được tạo<br>";
    echo "➡️ Tài khoản: <b>admin</b> | Mật khẩu: <b>admin123</b><br>";
} else {
    echo "ℹ️ Admin đã tồn tại<br>";
}

echo "<br>🎉 <b>Khởi tạo Database & Admin thành công!</b>";
?>
