<?php
session_start();
$db = new PDO('sqlite:db/database.sqlite');

if(isset($_POST['username'], $_POST['password'])){
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($_POST['password'], $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>

<link rel="stylesheet" href="style.css">

<div class="header">
    <div class="logo">Scan2Text</div>
    <nav>
        <a href="register.php">Đăng ký</a>
    </nav>
</div>

<div class="container">
    <h2>Đăng nhập</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>

    <?php if(isset($error)): ?>
        <p style="color:red; text-align:center; margin-top:15px;"><?php echo $error; ?></p>
    <?php endif; ?>

    <p style="text-align:center; margin-top:20px;">
        Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
    </p>
</div>
