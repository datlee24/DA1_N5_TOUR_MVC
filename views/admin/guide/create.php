<?php headerAdmin(); ?>

<h2 class="mt-4">Thêm Hướng dẫn viên</h2>

<div class="card mt-3">
    <div class="card-body">
        <form action="admin.php?act=guide-store" method="POST">

            <div class="mb-3">
                <label>Họ và tên </label>
                <input type="text" name="fullname" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email (dùng để tạo tài khoản HDV)</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Số điện thoại</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="mb-3">
                <label>Tên đăng nhập (username) — tùy chọn</label>
                <input type="text" name="username" class="form-control">
            </div>

            <div class="mb-3">
                <label>Mật khẩu (nếu để trống sẽ sinh tự động)</label>
                <input type="text" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Ngôn ngữ</label>
                <input type="text" name="language" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Giấy phép hành nghề</label>
                <input type="text" name="certificate" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Kinh nghiệm</label>
                <textarea name="experience" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label>Chuyên môn</label>
                <input type="text" name="specialization" class="form-control" required>
            </div>

            <button class="btn btn-primary">Lưu</button>
        </form>
    </div>
</div>

<?php footerAdmin(); ?>