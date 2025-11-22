<?php
headerAdmin();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="d-flex justify-content-between align-items-center mt-4">
    <h1 class="h3">Thêm hướng dẫn viên</h1>
    <a href="admin.php?act=guide" class="btn btn-secondary">Quay lại danh sách</a>
</div>

<div class="card mt-4">
    <div class="card-body">
        <form action="admin.php?act=guide-store" method="POST">
            <div class="mb-3">
                <label for="user_id" class="form-label">Nhân sự</label>
                <select class="form-select <?= isset($errors['user_id']) ? 'is-invalid' : ''; ?>" id="user_id" name="user_id">
                    <option value="">-- Chọn người dùng --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['user_id']; ?>"
                            <?= (isset($old['user_id']) && (int)$old['user_id'] === (int)$user['user_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($user['username'] ?? $user['email']); ?> (ID: <?= $user['user_id']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['user_id'])): ?>
                    <div class="invalid-feedback"><?= $errors['user_id']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="language" class="form-label">Ngôn ngữ</label>
                <input type="text" class="form-control <?= isset($errors['language']) ? 'is-invalid' : ''; ?>" id="language" name="language" value="<?= htmlspecialchars($old['language'] ?? ''); ?>">
                <?php if (isset($errors['language'])): ?>
                    <div class="invalid-feedback"><?= $errors['language']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="certificate" class="form-label">Số giấy phép hành nghề</label>
                <input type="text" class="form-control <?= isset($errors['certificate']) ? 'is-invalid' : ''; ?>" id="certificate" name="certificate" value="<?= htmlspecialchars($old['certificate'] ?? ''); ?>">
                <?php if (isset($errors['certificate'])): ?>
                    <div class="invalid-feedback"><?= $errors['certificate']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="specialization" class="form-label">Chuyên môn/Khu vực</label>
                <input type="text" class="form-control <?= isset($errors['specialization']) ? 'is-invalid' : ''; ?>" id="specialization" name="specialization" value="<?= htmlspecialchars($old['specialization'] ?? ''); ?>">
                <?php if (isset($errors['specialization'])): ?>
                    <div class="invalid-feedback"><?= $errors['specialization']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="base_fee" class="form-label">Thù lao cơ bản (đ/ngày)</label>
                <input type="number" min="0" step="0.01" class="form-control <?= isset($errors['base_fee']) ? 'is-invalid' : ''; ?>" id="base_fee" name="base_fee" value="<?= htmlspecialchars($old['base_fee'] ?? ''); ?>">
                <?php if (isset($errors['base_fee'])): ?>
                    <div class="invalid-feedback"><?= $errors['base_fee']; ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    </div>
</div>

<?php footerAdmin(); ?>

