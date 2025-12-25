<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php';
include __DIR__ . '/templates/header.php';

// Lấy ID user
if (!isset($_GET['id'])) {
    header('Location: users.php');
    exit;
}

$id = (int)$_GET['id'];

// Lấy thông tin user
$stmt = $db->prepare("SELECT * FROM users WHERE id = ? AND role = 'user'");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='alert alert-danger text-center'>❌ Người dùng không tồn tại</div>";
    include 'templates/footer.php';
    exit;
}

// Xử lý submit
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $status   = $_POST['status'];
    $oldPass  = $_POST['old_password'];
    $newPass  = $_POST['new_password'];

    // Kiểm tra mật khẩu cũ
    if (!password_verify($oldPass, $user['password'])) {
        $error = "❌ Mật khẩu cũ không đúng";
    } else {
        // Nếu có nhập mật khẩu mới → đổi
        if (!empty($newPass)) {
            $hashed = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET username=?, password=?, status=? WHERE id=?");
            $stmt->execute([$username, $hashed, $status, $id]);
        } else {
            // Không đổi mật khẩu
            $stmt = $db->prepare("UPDATE users SET username=?, status=? WHERE id=?");
            $stmt->execute([$username, $status, $id]);
        }

        $success = "✅ Cập nhật người dùng thành công";
        // Load lại dữ liệu
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<style>
.edit-box {
    max-width: 600px;        /* vẫn rộng thoáng */
    width: 90%;
    margin: 20px auto 40px;  /* cách trên 20px, cách dưới 40px */
    background: #fff;
    padding: 30px 25px;      /* giảm padding để form không quá cao */
    border-radius: 10px;
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
}
.edit-box h4 {
    text-align: center;
    margin-bottom: 20px;     /* giảm khoảng cách tiêu đề */
    font-size: 1.7rem;
    font-weight: 600;
}
</style>

<div class="edit-box">
    <h4>✏️ Chỉnh sửa người dùng</h4>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" class="form-control"
                   value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Mật khẩu cũ <span class="text-danger">*</span></label>
            <input type="password" name="old_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mật khẩu mới (để trống nếu không đổi)</label>
            <input type="password" name="new_password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
                <option value="active" <?= $user['status']=='active'?'selected':'' ?>>Hoạt động</option>
                <option value="blocked" <?= $user['status']=='blocked'?'selected':'' ?>>Bị khóa</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="users.php" class="btn btn-secondary">⬅ Quay lại</a>
            <button class="btn btn-primary">💾 Lưu thay đổi</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>
