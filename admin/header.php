<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? 'Admin';
$role = $_SESSION['role'] ?? '';
?>

<div class="admin-header">
    <h2>📊 Dashboard</h2>
    <div>
        👤 <?php echo htmlspecialchars($username); ?>
        <?php if ($role === 'admin'): ?>
            <span style="color:#6c63ff;">(Admin)</span>
        <?php endif; ?>
    </div>
</div>
<hr>
