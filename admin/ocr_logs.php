<?php
require 'auth.php';

// Kết nối DB
$db = new PDO('sqlite:' . __DIR__ . '/../db/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Lấy lịch sử OCR + username
$stmt = $db->query("
    SELECT o.id, u.username, o.image_path, o.result, o.created_at
    FROM ocr_history o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC
");

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="style.css">

<div class="header">
    <div class="logo">Admin Panel</div>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="users.php">Người dùng</a>
        <a href="ocr_logs.php">Lịch sử OCR</a>
        <a href="logout.php">Đăng xuất</a>
    </nav>
</div>

<div class="container">
    <h2>Lịch sử nhận dạng OCR</h2>

    <?php if (empty($logs)): ?>
        <p style="text-align:center;">Chưa có dữ liệu OCR.</p>
    <?php else: ?>
        <?php foreach ($logs as $log): ?>
            <div style="
                background:#fff;
                padding:15px;
                border-radius:12px;
                margin-bottom:20px;
                box-shadow:0 5px 15px rgba(0,0,0,0.05);
            ">
                <p><strong>Người dùng:</strong> <?php echo htmlspecialchars($log['username']); ?></p>
                <p><strong>Thời gian:</strong> <?php echo $log['created_at']; ?></p>

                <img src="../<?php echo $log['image_path']; ?>" style="max-width:100%; margin:10px 0;">

                <textarea
                    readonly
                    style="
                        width:100%;
                        min-height:180px;
                        resize:none;
                        font-family:'Times New Roman';
                        font-size:13pt;
                        color:#000;
                        text-align:justify;
                        padding:12px;
                        border-radius:10px;
                        border:1px solid #ccc;
                    "
                ><?php echo htmlspecialchars($log['result']); ?></textarea>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
