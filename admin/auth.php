<?php
session_start();
require __DIR__ . '/../init_db.php';

/* ===== CHƯA ĐĂNG NHẬP ===== */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

/* ===== KIỂM TRA USER TRONG DB ===== */
$stmt = $db->prepare("SELECT role, status FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* ===== USER KHÔNG TỒN TẠI ===== */
if (!$user) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

/* ===== USER BỊ KHÓA ===== */
if ($user['status'] === 'blocked') {
    session_destroy();
    die("⛔ Tài khoản đã bị khóa");
}

/* ===== KHÔNG PHẢI ADMIN ===== */
if ($user['role'] !== 'admin') {
    die("⛔ Truy cập bị từ chối");
}
