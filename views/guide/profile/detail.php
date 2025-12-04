<?php headerGuide(); ?>

<style>
.profile-card {
    border-radius: 14px;
    padding: 25px;
}
.profile-label {
    font-weight: 600;
    color: #6c757d;
}
.profile-value {
    font-size: 1.05rem;
    font-weight: 600;
}
</style>

<div class="container mt-4">

    <h3 class="fw-bold mb-4">👤 Hồ sơ cá nhân</h3>

    <div class="card shadow-sm profile-card">

        <h4 class="mb-3 text-primary"><?= htmlspecialchars($profile['fullname']) ?></h4>

        <div class="row mb-2">
            <div class="col-md-4 profile-label">📱 Số điện thoại</div>
            <div class="col-md-8 profile-value"><?= htmlspecialchars($profile['phone']) ?></div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 profile-label">📧 Email</div>
            <div class="col-md-8 profile-value"><?= htmlspecialchars($profile['email']) ?></div>
        </div>

        <hr>

        <div class="row mb-2">
            <div class="col-md-4 profile-label">🌐 Ngoại ngữ</div>
            <div class="col-md-8 profile-value">
                <?= htmlspecialchars($profile['language'] ?? 'Không có') ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 profile-label">🎓 Chứng chỉ</div>
            <div class="col-md-8 profile-value">
                <?= htmlspecialchars($profile['certificate'] ?? 'Không có') ?>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 profile-label">🧭 Kinh nghiệm</div>
            <div class="col-md-8 profile-value">
                <?= htmlspecialchars($profile['experience'] ?? 'Không có') ?> năm
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-md-4 profile-label">⭐ Chuyên môn</div>
            <div class="col-md-8 profile-value">
                <?= htmlspecialchars($profile['specialization'] ?? 'Không có') ?>
            </div>
        </div>

        <div class="mt-4">
            <a href="index.php?act=profile-edit" class="btn btn-primary">
                ✏ Cập nhật hồ sơ
            </a>
            <a href="index.php" class="btn btn-outline-secondary">← Quay lại</a>
        </div>
    </div>
</div>

<?php footerGuide(); ?>
