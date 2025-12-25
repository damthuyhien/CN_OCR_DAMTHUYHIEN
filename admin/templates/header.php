<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trang Quản Trị</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* Navbar đẹp hơn */
.navbar {
    background: linear-gradient(90deg, #6a11cb, #2575fc);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    position: sticky;
    top: 0;
    z-index: 1000;
}

/* Logo / Brand */
.navbar-brand {
    font-weight: bold;
    font-size: 1.5rem;
    letter-spacing: 1px;
    color: #fff;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
}

/* Menu items */
.navbar-nav .nav-link {
    color: #fff;
    font-weight: 500;
    margin-right: 10px;
    position: relative;
    transition: all 0.3s ease;
}

.navbar-nav .nav-link::after {
    content: '';
    display: block;
    width: 0;
    height: 2px;
    background: #fff;
    transition: width 0.3s;
    position: absolute;
    bottom: 0;
    left: 0;
}

.navbar-nav .nav-link:hover {
    color: #ffd700; /* vàng nổi bật khi hover */
}

.navbar-nav .nav-link:hover::after {
    width: 100%;
}

/* Container nội dung */
.container {
    margin-top: 20px;
}

/* Hiệu ứng di chuột trên card / button nội dung */
.card, .btn {
    transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover, .btn:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <a class="navbar-brand ms-3" href="index.php">Admin Panel</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto me-3">
      <li class="nav-item"><a class="nav-link" href="dashboard.php">Bảng Điều Khiển</a></li>
      <li class="nav-item"><a class="nav-link" href="users.php">Người Dùng</a></li>
      <li class="nav-item"><a class="nav-link" href="ocr_logs.php">Lịch Sử OCR</a></li>
      <li class="nav-item"><a class="nav-link" href="invalid_data.php">Dữ Liệu Sai</a></li>
      <li class="nav-item"><a class="nav-link" href="stats.php">Thống Kê</a></li>
      <li class="nav-item"><a class="nav-link" href="performance.php">Hiệu Suất</a></li>
      <li class="nav-item"><a class="nav-link" href="/CN/logout.php">Đăng Xuất</a></li>
    </ul>
  </div>
</nav>

<div class="container mt-4">
  <!-- Nội dung dashboard sẽ nằm ở đây -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
