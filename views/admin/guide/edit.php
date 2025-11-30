<?php headerAdmin(); ?>

<h2 class="mt-4">Cập nhật Hướng dẫn viên</h2>

<div class="card mt-3">
    <div class="card-body">
        <form action="admin.php?act=guide-update&id=<?= $guide['guide_id']; ?>" method="POST">

            <div class="mb-3">
                <label>Họ và tên</label>
                <input type="text" name="fullname" class="form-control" value="<?= $guide['fullname']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Ngôn ngữ</label>
                <input type="text" name="language" class="form-control" value="<?= $guide['language']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Giấy phép hành nghề</label>
                <input type="text" name="certificate" class="form-control" value="<?= $guide['certificate']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Kinh nghiệm</label>
                <textarea name="experience" class="form-control" required><?= $guide['experience']; ?></textarea>
            </div>

            <div class="mb-3">
                <label>Chuyên môn</label>
                <input type="text" name="specialization" class="form-control" value="<?= $guide['specialization']; ?>" required>
            </div>

            <button class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>

<?php footerAdmin(); ?>
