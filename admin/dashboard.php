<?php
require __DIR__ . '/../init_db.php';
require __DIR__ . '/auth.php';
include __DIR__ . '/templates/header.php';

// =================== Thống kê ===================
$tongNguoiDung = $db->query("
    SELECT COUNT(*) FROM users 
    WHERE role = 'user'
")->fetchColumn();

$tongHoaDon = $db->query("SELECT COUNT(*) FROM ocr_history")->fetchColumn();
$loiHomNay = $db->query("SELECT COUNT(*) FROM ocr_history WHERE status='error' AND DATE(created_at)=DATE('now')")->fetchColumn();
$thanhCong = $db->query("SELECT COUNT(*) FROM ocr_history WHERE status='success'")->fetchColumn();
$tyLeThanhCong = $tongHoaDon ? round($thanhCong/$tongHoaDon*100,1) : 0;

// =================== Lấy dữ liệu chart tuần ===================
$hoaDonTheoNgay = [];
$labels = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'];

$today = new DateTime();
$dayOfWeek = (int)$today->format('N'); // 1 = Thứ 2 ... 7 = Chủ nhật
$monday = clone $today;
$monday->modify('-'.($dayOfWeek-1).' days'); // lấy ngày Thứ 2

for ($i=0; $i<7; $i++) {
    $date = $monday->format('Y-m-d');
    $stmt = $db->prepare("SELECT COUNT(*) FROM ocr_history WHERE DATE(created_at) = ?");
    $stmt->execute([$date]);
    $hoaDonTheoNgay[] = (int)$stmt->fetchColumn();
    $monday->modify('+1 day');
}
?>

<style>
body {
    background: linear-gradient(to bottom right, #f0f4f8, #d9e2ec);
    min-height: 100vh;
}
.dashboard-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin-top: 20px;
}
.dashboard-card {
    flex: 1 1 220px;
    max-width: 260px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    padding: 20px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}
.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.dashboard-card img {
    margin-bottom: 15px;
}
canvas {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}
</style>

<div class="dashboard-row">
  <div class="dashboard-card">
    <img src="assets/images/user_icon.png" width="50">
    <h5>Người dùng</h5>
    <p><?= $tongNguoiDung ?></p>
  </div>
  <div class="dashboard-card">
    <img src="assets/images/invoice_icon.png" width="50">
    <h5>Hóa đơn</h5>
    <p><?= $tongHoaDon ?></p>
  </div>
  <div class="dashboard-card">
    <img src="assets/images/error_icon.png" width="50">
    <h5>Lỗi hôm nay</h5>
    <p><?= $loiHomNay ?></p>
  </div>
  <div class="dashboard-card">
    <img src="assets/images/success_icon.png" width="50">
    <h5>Tỷ lệ thành công</h5>
    <p><?= $tyLeThanhCong ?>%</p>
  </div>
</div>

<div class="mt-4" style="max-width:1000px; width:90%; margin:30px auto; height:250px;">
  <canvas id="hoaDonChart"></canvas>
</div>

<script>
const ctx = document.getElementById('hoaDonChart').getContext('2d');
const hoaDonChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Số hóa đơn mỗi ngày',
            data: <?= json_encode($hoaDonTheoNgay) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { font: { size: 14 } } },
            tooltip: { mode: 'index', intersect: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#e0e0e0' } },
            x: { grid: { color: '#f0f0f0' } }
        }
    }
});
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>
