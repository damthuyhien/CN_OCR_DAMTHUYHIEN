<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

// ===================
// LẤY USER THƯỜNG (KHÔNG ADMIN)
// ===================
$users = $db->query("
    SELECT id, username 
    FROM users 
    WHERE role = 'user'
")->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$data   = [];

foreach ($users as $u) {
    $stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM ocr_history 
        WHERE user_id = ?
    ");
    $stmt->execute([$u['id']]);

    $labels[] = $u['username'];
    $data[]   = (int)$stmt->fetchColumn();
}
?>

<style>
.stats-box {
    max-width: 900px;
    width: 95%;
    margin: 30px auto;
    background: #fff;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}
.stats-box h4 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
}
</style>

<div class="stats-box">
    <h4>📊 Thống kê số hóa đơn OCR theo người dùng</h4>

    <?php if (empty($labels)): ?>
        <p class="text-center text-muted">
            Chưa có dữ liệu OCR
        </p>
    <?php else: ?>
        <canvas id="userChart" height="120"></canvas>
    <?php endif; ?>
</div>

<script>
<?php if (!empty($labels)): ?>
const ctx = document.getElementById('userChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Số hóa đơn OCR',
            data: <?= json_encode($data) ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
<?php endif; ?>
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>
