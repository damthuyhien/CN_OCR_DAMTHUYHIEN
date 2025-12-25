<?php
$db = new PDO('sqlite:db/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// thêm created_at nếu chưa có
$cols = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_COLUMN, 1);

if (!in_array('created_at', $cols)) {
    $db->exec("ALTER TABLE users ADD COLUMN created_at TEXT");
    echo "✅ Đã thêm created_at<br>";
}

// cập nhật user cũ nếu null
$db->exec("
    UPDATE users 
    SET created_at = datetime('now','localtime')
    WHERE created_at IS NULL
");

echo "🎉 Database OK";
