<?php
session_start();
if ($_SESSION['user']['role'] !== 'admin') die("⛔");

$db = new PDO('sqlite:' . __DIR__ . '/../db/database.sqlite');


$data = $db->query("
SELECT ocr_history.*, users.username 
FROM ocr_history 
JOIN users ON users.id = ocr_history.user_id
ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

require 'auth.php'; 
?>

<h2>📄 Lịch sử OCR toàn hệ thống</h2>

<table border="1" cellpadding="5">
<tr>
    <th>User</th>
    <th>Ảnh</th>
    <th>Kết quả</th>
    <th>Thời gian</th>
</tr>

<?php foreach ($data as $row): ?>
<tr>
    <td><?= $row['username'] ?></td>
    <td><?= basename($row['image_path']) ?></td>
    <td><?= mb_substr($row['result'], 0, 100) ?>...</td>
    <td><?= $row['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<a href="dashboard.php">⬅️ Quay lại</a>
