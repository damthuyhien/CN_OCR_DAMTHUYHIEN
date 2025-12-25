<?php
require 'auth.php';

$db = new PDO('sqlite:' . __DIR__ . '/../db/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <?php include 'sidebar.php'; ?>

    <div class="content">
        <h2>📊 Tổng quan hệ thống</h2>

        <div class="cards">
            <div class="card">
                <h3>👤 Người dùng</h3>
                <p>124</p>
            </div>
            <div class="card">
                <h3>📄 Lượt OCR</h3>
                <p>2,431</p>
            </div>
            <div class="card">
                <h3>🧠 Độ chính xác</h3>
                <p>96%</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>

