<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

/* ===== LẤY TOÀN BỘ DỮ LIỆU OCR ===== */
$records = $db->query("
    SELECT 
        o.id,
        u.username AS user_name,
        it.name AS invoice_type,
        o.status,
        o.created_at
    FROM ocr_history o
    LEFT JOIN users u ON u.id = o.user_id
    LEFT JOIN invoice_types it ON it.id = o.invoice_type_id
    ORDER BY o.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.history-box{
    max-width:1200px;
    margin:30px auto;
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 16px 40px rgba(0,0,0,.12);
}
.history-box h4{
    text-align:center;
    margin-bottom:25px;
    font-weight:700;
}
.badge-ok{
    background:#22c55e;
    color:#fff;
    padding:5px 10px;
    border-radius:8px;
    font-size:13px;
}
.badge-error{
    background:#ef4444;
    color:#fff;
    padding:5px 10px;
    border-radius:8px;
    font-size:13px;
}
.action-btns{
    display:flex;
    gap:8px;
    justify-content:center;
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
    <th>Trạng thái</th>
    <th>Ngày</th>
    <th>Thao tác</th>
</tr>
</thead>

<tbody>
<?php if(empty($records)): ?>
<tr>
    <td colspan="6" class="text-muted">Chưa có dữ liệu OCR</td>
</tr>
<?php else: foreach($records as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['user_name'] ?? 'N/A') ?></td>
    <td><?= htmlspecialchars($r['invoice_type'] ?? 'Chưa phân loại') ?></td>
    <td>
        <?php if($r['status'] === 'error'): ?>
            <span class="badge-error">Lỗi dữ liệu</span>
        <?php else: ?>
            <span class="badge-ok">Hợp lệ</span>
        <?php endif; ?>
    </td>
    <td><?= $r['created_at'] ?></td>
    <td>
        <div class="action-btns">

            <!-- XEM CHI TIẾT -->
            <a href="ocr_detail.php?ocr_id=<?= $r['id'] ?>"
               class="btn btn-sm btn-primary">
               👁 Xem
            </a>

            <!-- ĐÁNH DẤU SAI -->
            <?php if($r['status'] !== 'error'): ?>
                <a href="mark_invalid.php?ocr_id=<?= $r['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Xác nhận dữ liệu OCR này là sai?')">
                   🚫 Sai
                </a>
            <?php else: ?>
                <span class="text-muted">Đã xử lý</span>
            <?php endif; ?>

        </div>
    </td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
