<?php headerAdmin(); ?>

<div class="container mt-4">

    <h1 class="h3 mb-3">Thêm khách hàng</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="admin.php?act=customer-store" method="POST">

                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">— Chọn —</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birthdate" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">CMND/CCCD</label>
                    <input type="text" name="id_number" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

                <button class="btn btn-primary">Lưu</button>
                <a href="admin.php?act=customer" class="btn btn-secondary">Quay lại</a>

            </form>

        </div>
    </div>

</div>

<?php footerAdmin(); ?>
