<?php 
require __DIR__ . '/auth.php';
require __DIR__ . '/../init_db.php'; // đường dẫn đến file init_db.php
include __DIR__ . '/templates/header.php';

// Lấy tất cả người dùng bình thường (role = 'user')
$users = $db->query("SELECT * FROM users WHERE role='user'")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.table-hover tbody tr:hover {
    background-color: #f0f8ff;
    transition: 0.3s;
}
.btn-action {
    margin-right: 5px;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Quản lý Người Dùng</h3>
    <a href="add_users.php" class="btn btn-success">
        <i class="bi bi-person-plus-fill"></i> Thêm Người Dùng
    </a>
</div>

<table class="table table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>Avatar</th>
            <th>Tên đăng nhập</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $u): ?>
        <tr>
            <td><img src="assets/images/avatar/<?= $u['avatar'] ?>" width="40" class="rounded-circle"></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
<td>
<?php if ($u['status'] === 'active'): ?>
    <span class="badge bg-success">Hoạt động</span>
<?php elseif ($u['status'] === 'blocked'): ?>
    <span class="badge bg-danger">Bị khóa</span>
<?php else: ?>
    <span class="badge bg-secondary">Không xác định</span>
<?php endif; ?>
</td>
            <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
            <td>
                <a href="edit_users.php?id=<?= $u['id'] ?>" class="btn btn-primary btn-sm btn-action">Sửa</a>
                <a href="delete_users.php?id=<?= $u['id'] ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/templates/footer.php'; ?>
