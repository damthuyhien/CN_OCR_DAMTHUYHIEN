<?php
session_start();
$db = new PDO('sqlite:db/database.sqlite');
// Bật chế độ ném lỗi để bắt exception
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['username'], $_POST['password'], $_POST['password_confirm'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Kiểm tra mật khẩu nhập lại
    if($password !== $password_confirm){
        $error = "Mật khẩu nhập lại không khớp!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password_hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            // Nếu username đã tồn tại (vi phạm UNIQUE constraint)
            if(strpos($e->getMessage(), 'UNIQUE') !== false){
                $error = "Tên người dùng đã tồn tại!";
            } else {
                $error = "Đã xảy ra lỗi: " . $e->getMessage();
            }
        }
    }
}
?>

<link rel="stylesheet" href="style.css">

<div class="header">
    <div class="logo">Scan2Text</div>
    <nav>
        <a href="login.php">Đăng nhập</a>
    </nav>
</div>

<div class="container">
    <h2>Đăng ký tài khoản</h2>

    <div class="register-box" style="background:#fff; padding:25px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.05); max-width:400px; margin:auto;">
        <form method="POST" style="display:flex; flex-direction:column; gap:15px;">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <input type="password" name="password_confirm" placeholder="Nhập lại mật khẩu" required>
            <button type="submit">Đăng ký</button>
        </form>

        <?php if(isset($error)): ?>
            <p style="color:red; text-align:center; margin-top:15px;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>

    <p style="text-align:center; margin-top:20px;">
        Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
    </p>
</div>
