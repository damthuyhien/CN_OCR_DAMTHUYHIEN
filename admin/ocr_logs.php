<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

$records = $db->query("
    SELECT 
        o.id,
        u.username AS user_name,
        o.invoice_type,
        o.image_path,
        o.status,
        o.created_at
    FROM ocr_history o
    LEFT JOIN users u ON u.id = o.user_id
    ORDER BY o.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.history-box {
    max-width: 1100px;
    margin: 20px auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 14px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}
.history-box h4 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
}
.history-box img {
    border-radius: 6px;
    object-fit: cover;
}
</style>

<div class="history-box">
    <h4>📄 Lịch sử OCR</h4>

    <table class="table table-hover align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Loại hóa đơn</th>
                <th>Ảnh</th>
                <th>Trạng thái</th>
                <th>Ngày</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($records)): ?>
            <tr>
                <td colspan="6" class="text-muted">Chưa có dữ liệu OCR</td>
            </tr>
        <?php else: ?>
           <?php foreach($records as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= $r['user_name'] ?></td>
    <td><?= $r['invoice_type'] ?></td>
    <td><img src="<?= $r['image_path'] ?>" width="60"></td>
    <td><?= $r['status'] ?></td>
    <td><?= $r['created_at'] ?></td>
    <td>
        <?php if($r['status'] !== 'error'): ?>
            <a href="invalid_data.php?ocr_id=<?= $r['id'] ?>" 
               class="btn btn-sm btn-danger">
               🚫 Dữ liệu sai
            </a>
        <?php else: ?>
            <span class="text-muted">Đã lỗi</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
