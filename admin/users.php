<?php
require 'auth.php';

$db = new PDO('sqlite:' . __DIR__ . '/../db/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
 Chỉ lấy USER
 Không lấy ADMIN
*/
$stmt = $db->query("
    SELECT id, username, role
    FROM users
    WHERE role = 'user'
    ORDER BY id DESC
");

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require 'header.php'; ?>
<?php require 'sidebar.php'; ?>

<div class="main-content">
    <h2 style="margin-bottom:20px;">👤 Quản lý người dùng</h2>

    <div class="card">
        <table class="vip-table">

            <tbody>
                <?php if (count($users) === 0): ?>
                    <tr>
                        <td colspan="3" style="text-align:center;">Không có người dùng</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>
                            <span class="badge user">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.vip-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
}

.vip-table th {
    background: #f5f7fb;
    text-align: left;
    padding: 14px;
    font-weight: 600;
}

.vip-table td {
    padding: 14px;
    border-top: 1px solid #eee;
}

.badge.user {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
}
</style>
