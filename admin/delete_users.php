<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php'; // kết nối DB

if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$id = (int)$_GET['id'];

// Lấy thông tin user
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("❌ Người dùng không tồn tại");
}

// Không xóa admin
if ($user['role'] === 'admin') {
    die("❌ Không thể xóa admin");
}

// Xóa user
$stmt = $db->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header("Location: users.php?msg=" . urlencode("✅ Xóa thành công người dùng " . $user['username']));
exit;
?>
