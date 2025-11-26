<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }

$db = new PDO('sqlite:db/database.sqlite');
$stmt = $db->prepare("SELECT * FROM ocr_history WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="style.css">

<div class="header">
    <div class="logo">Scan2Text</div>
    <nav>
        <a href="upload.php">Tải ảnh OCR</a>
        <a href="index.php">Trang chủ</a>
        <a href="logout.php">Đăng xuất</a>
    </nav>
</div>

<div class="container">
<h2>Lịch sử OCR</h2>

<?php if($records): ?>
    <?php foreach($records as $r): ?>
        <div class="history" style="border-radius:12px; background:#fff; padding:15px; margin:15px 0; box-shadow:0 5px 15px rgba(0,0,0,0.05);">
            <img src="<?php echo $r['image_path']; ?>" style="max-width:100%; border-radius:12px;"><br>
            <strong>Kết quả OCR:</strong>
            <pre><?php echo htmlspecialchars($r['result']); ?></pre>
            <small>Ngày: <?php echo $r['created_at']; ?></small>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Chưa có lịch sử OCR.</p>
<?php endif; ?>

<p style="text-align:center; margin-top:20px;">
    <a href="index.php"><button>Trang chủ</button></a>
</p>
</div>
