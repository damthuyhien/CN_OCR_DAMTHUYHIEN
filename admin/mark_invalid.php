<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';

if (!isset($_GET['ocr_id'])) {
    header("Location: ocr_logs.php");
    exit;
}

$ocrId = (int)$_GET['ocr_id'];
$adminId = $_SESSION['user_id'];

/* ==========================
   1. UPDATE TRẠNG THÁI OCR
========================== */
$stmt = $db->prepare("
    UPDATE ocr_history
    SET status = 'error'
    WHERE id = ?
");
$stmt->execute([$ocrId]);

/* ==========================
   2. KIỂM TRA ĐÃ CÓ INVALID CHƯA
========================== */
$check = $db->prepare("
    SELECT COUNT(*) 
    FROM invalid_data 
    WHERE ocr_id = ?
");
$check->execute([$ocrId]);

if ($check->fetchColumn() == 0) {

    /* ==========================
       3. INSERT VÀO INVALID_DATA
    ========================== */
    $stmt = $db->prepare("
        INSERT INTO invalid_data (ocr_id, issue, checked_by)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([
        $ocrId,
        'Dữ liệu OCR không chính xác',
        $adminId
    ]);
}

/* ==========================
   4. CHUYỂN SANG TRANG DỮ LIỆU SAI
========================== */
header("Location: invalid_data.php");
exit;
