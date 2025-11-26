<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
?>

<link rel="stylesheet" href="style.css">

<body class="theme-light">
<div class="header">
    <div class="logo">Scan2Text</div>
    <nav>
        <?php if($logged_in): ?>
            <span>Xin chào, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php">Đăng xuất</a>
        <?php else: ?>
            <a href="login.php">Đăng nhập</a>
            <a href="register.php">Đăng ký</a>
        <?php endif; ?>
        <button id="theme-toggle">🌞</button>
    </nav>
</div>

<div class="container">
    <h1>Chào mừng đến Scan2Text!</h1>
    <p style="text-align:center; margin:20px 0; font-size:1.2em;">
        Nhận dạng ký tự từ hình ảnh OCR nhanh chóng, hỗ trợ tiếng Việt và tiếng Anh.
    </p>

    <div class="flex-center">
        <a href="<?php echo $logged_in ? 'upload.php' : 'login.php'; ?>"><button class="main-btn">Tải ảnh OCR</button></a>
        <a href="<?php echo $logged_in ? 'history.php' : 'login.php'; ?>"><button class="main-btn">Xem lịch sử OCR</button></a>
    </div>

    <section class="about">
        <h2>Giới thiệu</h2>
        <p>
Scan2Text là công cụ OCR tối ưu dành cho mọi nhu cầu chuyển đổi hình ảnh thành văn bản. Với khả năng nhận dạng ký tự mạnh mẽ và độ chính xác cao, Scan2Text giúp bạn số hóa tài liệu, bài viết, hóa đơn hay ghi chú nhanh chóng chỉ với vài cú click. Không chỉ dừng lại ở việc nhận dạng, website còn hỗ trợ lưu lịch sử quét, quản lý kết quả thông minh và xuất dữ liệu dễ dàng. Giao diện hiện đại với tông xanh biển tươi mát, kết hợp chế độ sáng/tối linh hoạt, mang đến trải nghiệm thân thiện và dễ chịu cho người dùng. Scan2Text là giải pháp hoàn hảo cho học tập, công việc và mọi nhu cầu số hóa thông tin, giúp bạn tiết kiệm thời gian và nâng cao hiệu quả tối đa        </p>
    </section>
</div>

<script>
    const toggle = document.getElementById('theme-toggle');

toggle.addEventListener('click', () => {
    document.body.classList.toggle('theme-dark');
    document.body.classList.toggle('theme-light');

    if(document.body.classList.contains('theme-dark')){
        toggle.textContent = "🌙"; 
    } else {
        toggle.textContent = "🌞"; 
    }
});

</script>
