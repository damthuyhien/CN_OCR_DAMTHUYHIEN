<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $status   = $_POST['status'] ?? 'active';

    if ($username === '' || $password === '') {
        $error = "❌ Vui lòng nhập đầy đủ thông tin";
    } else {

        // Kiểm tra trùng username
        $check = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $check->execute([$username]);

        if ($check->fetchColumn() > 0) {
            $error = "❌ Tên đăng nhập đã tồn tại";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            /* ================= AVATAR ================= */
            $avatar = 'default.png';
            $uploadDir = __DIR__ . '/assets/images/avatar/';

            // tạo thư mục nếu chưa có
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] === 0) {

                $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($ext, $allowed)) {
                    $avatar = uniqid('av_') . '.' . $ext;
                    move_uploaded_file(
                        $_FILES['avatar']['tmp_name'],
                        $uploadDir . $avatar
                    );
                }
            }
            /* =========================================== */

            $stmt = $db->prepare("
                INSERT INTO users (username, password, role, status, avatar, created_at)
                VALUES (?, ?, 'user', ?, ?, datetime('now','localtime'))
            ");
            $stmt->execute([$username, $hashed, $status, $avatar]);

            $success = "✅ Thêm người dùng thành công";
        }
    }
}
?>

<style>
.add-box {
    max-width: 650px;
    width: 95%;
    margin: 20px auto 10px; /* sát footer hơn */
    background: #fff;
    padding: 30px 35px;
    border-radius: 14px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.add-box h4 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 1.8rem;
    font-weight: 600;
}
</style>

<div class="add-box">
    <h4>➕ Thêm người dùng</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="active">Hoạt động</option>
                <option value="blocked">Bị khóa</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Ảnh đại diện</label>
            <input type="file" name="avatar" class="form-control" accept="image/*">
        </div>

        <div class="d-flex justify-content-between">
            <a href="users.php" class="btn btn-secondary">⬅ Quay lại</a>
            <button type="submit" class="btn btn-success">➕ Thêm người dùng</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
