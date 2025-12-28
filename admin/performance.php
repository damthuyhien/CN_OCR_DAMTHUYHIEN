<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

// ====== DỮ LIỆU ======
// ====== DỮ LIỆU ======
$total = $db->query("
    SELECT COUNT(*) 
    FROM ocr_history
")->fetchColumn();

$today = $db->query("
    SELECT COUNT(*) 
    FROM ocr_history 
    WHERE date(created_at) = date('now','localtime')
")->fetchColumn();

$valid = $db->query("
    SELECT COUNT(*) 
    FROM ocr_history 
    WHERE status != 'error' OR status IS NULL
")->fetchColumn();

$invalid = $db->query("
    SELECT COUNT(*) 
    FROM ocr_history 
    WHERE status = 'error'
")->fetchColumn();

$errorRate = $total > 0 
    ? round(($invalid / $total) * 100, 2) 
    : 0;
?>

<!-- ===== STYLE ===== -->
<style>
.dashboard {
    max-width: 1100px;
    margin: 30px auto;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.card {
    background: white;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    text-align: center;
}

.card h5 {
    font-size: 15px;
    color: #777;
    margin-bottom: 8px;
}

.card h2 {
    font-size: 32px;
    margin: 0;
    font-weight: 700;
}

.card.total { border-left: 6px solid #0d6efd; }
.card.today { border-left: 6px solid #198754; }
.card.invalid { border-left: 6px solid #dc3545; }
.card.rate { border-left: 6px solid #fd7e14; }

.chart-box {
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
</style>

<div class="dashboard">
    <h3 class="mb-4">📊 Hiệu suất hệ thống OCR</h3>

    <!-- ===== CARDS ===== -->
    <div class="cards">
        <div class="card total">
            <h5>Tổng hóa đơn</h5>
            <h2><?= $total ?></h2>
        </div>

        <div class="card today">
            <h5>Hóa đơn hôm nay</h5>
            <h2><?= $today ?></h2>
        </div>

        <div class="card invalid">
            <h5>Hóa đơn lỗi</h5>
            <h2><?= $invalid ?></h2>
        </div>

        <div class="card rate">
            <h5>Tỉ lệ lỗi</h5>
            <h2><?= $errorRate ?>%</h2>
        </div>
    </div>

    <!-- ===== CHART ===== -->
    <div class="chart-box">
        <h5 class="mb-3">Biểu đồ kết quả OCR</h5>
        <canvas id="ocrChart" height="120"></canvas>
    </div>
</div>

<!-- ===== CHART JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ocrChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Hợp lệ', 'Không hợp lệ'],
        datasets: [{
            label: 'Số hóa đơn',
            data: [<?= $valid ?>, <?= $invalid ?>],
            backgroundColor: [
                'rgba(25, 135, 84, 0.7)',
                'rgba(220, 53, 69, 0.7)'
            ],
            borderRadius: 8
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<?php include 'templates/footer.php'; ?>
