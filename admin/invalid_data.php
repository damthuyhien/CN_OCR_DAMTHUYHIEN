<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

/*
  Lấy dữ liệu sai
  ocr_id = ocr_history.id (ID hóa đơn)
*/
$sql = "
SELECT 
    invalid_data.id AS invalid_id,
    invalid_data.ocr_id,
    invalid_data.issue,
    invalid_data.created_at,
    ocr_history.image_path,
    ocr_history.invoice_type,
    ocr_history.user_id
FROM invalid_data
JOIN ocr_history ON invalid_data.ocr_id = ocr_history.id
ORDER BY invalid_data.created_at DESC
";

$stmt = $db->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Dữ liệu OCR không hợp lệ</h3>

<table class="table">
    <thead>
        <tr>
            <th>ID lỗi</th>
            <th>ID hóa đơn</th>
            <th>Loại hóa đơn</th>
            <th>Ảnh</th>
            <th>Lỗi phát hiện</th>
            <th>Thời gian</th>
        </tr>
    </thead>
    <tbody>
    <?php if(empty($rows)): ?>
        <tr>
            <td colspan="6">Không có dữ liệu sai</td>
        </tr>
    <?php else: ?>
        <?php foreach($rows as $r): ?>
        <tr>
            <td><?= $r['invalid_id'] ?></td>
            <td><?= $r['ocr_id'] ?></td>
            <td><?= htmlspecialchars($r['invoice_type']) ?></td>
            <td>
                <img src="<?= $r['image_path'] ?>" width="80">
            </td>
            <td><?= htmlspecialchars($r['issue']) ?></td>
            <td><?= $r['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>
