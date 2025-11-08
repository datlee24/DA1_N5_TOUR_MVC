<?php headerAdmin() ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<h1 class="mt-4">Chào Mừng Admin</h1>
<p>
</p>
<p>
    Chỉ có admin mới có quyền hạn vô đây  
</p>

<?php footerAdmin() ?>