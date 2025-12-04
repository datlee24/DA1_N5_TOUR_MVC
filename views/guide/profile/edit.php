<?php 
headerGuide(); 
$pageTitle = "Cập nhật hồ sơ";
?>

<div class="container mt-4" style="max-width: 700px;">

    <div class="card shadow-sm p-4">

        <h3 class="mb-3">👤 Cập nhật hồ sơ cá nhân</h3>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?act=profile-update" method="post">

            <!-- HỌ VÀ TÊN -->
            <div class="mb-3">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="fullname" required class="form-control"
                    value="<?= htmlspecialchars($guide['fullname'] ?? '') ?>">
            </div>

            <!-- SỐ ĐIỆN THOẠI -->
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" required class="form-control"
                    value="<?= htmlspecialchars($guide['phone'] ?? '') ?>">
            </div>

            <!-- EMAIL -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" required class="form-control"
                    value="<?= htmlspecialchars($guide['email'] ?? '') ?>">
            </div>

            <!-- PASSWORD MỚI -->
            <div class="mb-3">
                <label class="form-label">Mật khẩu (để trống nếu không đổi)</label>
                <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới (tùy chọn)">
            </div>

            <hr>

            <div class="d-flex justify-content-between">
                <a href="index.php?act=profile" class="btn btn-secondary">⬅ Quay lại hồ sơ</a>
                <button class="btn btn-primary">💾 Lưu thay đổi</button>
            </div>

        </form>

    </div>
</div>

<?php footerGuide(); ?>
