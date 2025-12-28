<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

if (!isset($_GET['ocr_id'])) {
    die("❌ Thiếu ID OCR");
}

$ocr_id = (int)$_GET['ocr_id'];

/* ===== LẤY DỮ LIỆU OCR ===== */
$stmt = $db->prepare("
    SELECT 
        o.id,
        o.image_path,
        o.invoice_type,
        o.result,
        o.status,
        o.created_at,
        u.username
    FROM ocr_history o
    LEFT JOIN users u ON u.id = o.user_id
    WHERE o.id = ?
");
$stmt->execute([$ocr_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("❌ Không tìm thấy dữ liệu OCR");
}

/* ===== XỬ LÝ ĐƯỜNG DẪN ẢNH (FIX LỖI KHÔNG HIỆN) ===== */
$imgName = basename($data['image_path']); 
$imgSrc  = "/CN/user/uploads/" . $imgName;
?>

<style>
.detail-box{
    max-width:1200px;
    margin:30px auto;
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 18px 45px rgba(0,0,0,.15);
}
.detail-box h4{
    text-align:center;
    margin-bottom:20px;
    font-weight:700;
}
.meta{
    font-size:14px;
    color:#444;
    margin-bottom:20px;
    line-height:1.6;
}
.detail-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
}
.ocr-image img{
    width:100%;
    border-radius:14px;
    box-shadow:0 12px 30px rgba(0,0,0,.25);
}
.ocr-text{
    background:#f8fafc;
    padding:22px;
    border-radius:14px;
    border:1px solid #e5e7eb;
    white-space:pre-wrap;
    font-family:"Segoe UI", Tahoma, Arial, sans-serif;
    font-size:15px;
    line-height:1.65;
    color:#111827;
    overflow:auto;
}
.back-btn{
    margin-top:25px;
    text-align:center;
}
.back-btn a{
    padding:10px 22px;
    border-radius:10px;
}
@media(max-width:900px){
    .detail-grid{
        grid-template-columns:1fr;
    }
}
</style>

<div class="detail-box">

<h4>🔍 Chi tiết OCR #<?= $data['id'] ?></h4>

<div class="meta">
    👤 <b>Người dùng:</b> <?= htmlspecialchars($data['username']) ?><br>
    📄 <b>Loại hóa đơn:</b> <?= htmlspecialchars($data['invoice_type']) ?><br>
    🕒 <b>Ngày:</b> <?= $data['created_at'] ?><br>
    📌 <b>Trạng thái:</b> <?= htmlspecialchars($data['status']) ?>
</div>

<div class="detail-grid">

    <!-- ẢNH -->
    <div class="ocr-image">
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Ảnh OCR">
    </div>

    <!-- TEXT -->
    <div class="ocr-text">
        <?= $data['result'] 
            ? htmlspecialchars($data['result']) 
            : '⚠️ Chưa có dữ liệu OCR' ?>
    </div>

</div>

<div class="back-btn">
    <a href="invalid_data.php" class="btn btn-secondary">⬅ Quay lại danh sách</a>
</div>

</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
