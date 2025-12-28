<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* Bố cục full-height */
html, body {
    height: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    background: linear-gradient(to bottom right, #f0f4f8, #d9e2ec);
}

#container {
    flex: 1 0 auto; /* container chiếm không gian còn lại */
    padding-bottom: 60px; /* tránh che footer */
}

/* Footer */
footer {
    flex-shrink: 0;
    background: linear-gradient(to right, #6a11cb, #2575fc);
    color: #fff;
    text-align: center;
    padding: 15px 0;
    font-size: 14px;
    box-shadow: 0 -4px 10px rgba(0,0,0,0.1);
}

footer .subtext {
    margin-top:5px;
    font-size:12px;
    opacity:0.8;
}
</style>
</head>
<body>

<div id="container" class="container">
    <!-- Nội dung dashboard ở đây -->
</div> <!-- /container -->

<footer>
    <div>© <?= date('Y') ?> Xây dựng phần mềm nhận dạng văn bản từ ảnh/hóa đơn.</div>
    <div class="subtext">Đàm Thúy Hiền - 110122074</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/admin.js"></script>
</body>
</html>
