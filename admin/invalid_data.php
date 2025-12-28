<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

/* ===============================
   LẤY DỮ LIỆU OCR KHÔNG HỢP LỆ
   KÈM TÊN NGƯỜI DÙNG VÀ LOẠI HÓA ĐƠN
================================ */
$sql = "
SELECT 
    invalid_data.id AS invalid_id,
    invalid_data.ocr_id,
    invalid_data.issue,
    invalid_data.created_at,
    ocr_history.user_id,
    u.username AS user_name,
    it.name AS invoice_type
FROM invalid_data
JOIN ocr_history ON invalid_data.ocr_id = ocr_history.id
LEFT JOIN users u ON u.id = ocr_history.user_id
LEFT JOIN invoice_types it ON it.id = ocr_history.invoice_type_id
ORDER BY invalid_data.created_at DESC
";

$stmt = $db->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Dữ liệu OCR không hợp lệ</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID lỗi</th>
            <th>ID hóa đơn</th>
            <th>Loại hóa đơn</th>
            <th>Người dùng</th>
            <th>Lỗi phát hiện</th>
            <th>Thời gian</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($rows)): ?>
        <tr>
            <td colspan="7" style="text-align:center;">Không có dữ liệu sai</td>
        </tr>
    <?php else: ?>
        <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['invalid_id'] ?></td>
            <td><?= $r['ocr_id'] ?></td>
            <td><?= htmlspecialchars($r['invoice_type'] ?? 'Chưa phân loại') ?></td>
            <td><?= htmlspecialchars($r['user_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($r['issue']) ?></td>
            <td><?= $r['created_at'] ?></td>
            <td>
                <a href="ocr_detail.php?ocr_id=<?= $r['ocr_id'] ?>" 
                   class="btn btn-sm btn-primary">
                    Xem chi tiết
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>
