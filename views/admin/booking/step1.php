<?php require_once PATH_ADMIN . "layout/header.php"; ?>

<div class="container-fluid px-4">

    <div class="mt-4 mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Bước 1: Chọn Tour & Khách sạn</h2>
        <a href="admin.php?act=booking" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Danh sách Booking
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="admin.php?act=booking-step1-save" id="step1Form">

                <!-- Chọn tour -->
                <div class="mb-3">
                    <label class="fw-semibold mb-1">Chọn Tour <span class="text-danger">*</span></label>
                    <select name="tour_id" id="tourSelect" class="form-select" required>
                        <option value="">-- Chọn tour --</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['tour_id'] ?>">
                                <?= $t['name'] ?> (<?= number_format($t['price']) ?> đ)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Chọn khách sạn -->
                <div class="mb-3">
                    <label class="fw-semibold mb-1">Chọn Khách Sạn</label>
                    <select name="hotel_id" id="hotelSelect" class="form-select">
                        <option value="">-- Chọn khách sạn --</option>
                    </select>
                    <small id="hotelLoading" class="text-muted d-none">Đang tải khách sạn...</small>
                </div>

                <!-- Chọn tài xế (nếu có) -->
                <div class="mb-4">
                    <label class="fw-semibold mb-1">Chọn Tài Xế</label>
                    <select name="driver_id" class="form-select">
                        <option value="">-- Không chọn tài xế --</option>
                        <?php foreach ($drivers as $d): ?>
                            <option value="<?= $d['driver_id'] ?>">
                                <?= $d['fullname'] ?> — <?= $d['license_plate'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-lg px-4">
                        Tiếp tục <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
// Khi chọn tour → load khách sạn qua AJAX
document.getElementById("tourSelect").addEventListener("change", function () {
    let tourId = this.value;
    let hotelSelect = document.getElementById("hotelSelect");
    let loading = document.getElementById("hotelLoading");

    hotelSelect.innerHTML = '<option value="">-- Chọn khách sạn --</option>';

    if (!tourId) return;

    loading.classList.remove('d-none');

    fetch("admin.php?act=ajax-hotels&tour_id=" + tourId)
        .then(res => res.json())
        .then(json => {
            loading.classList.add('d-none');

            if (!json.ok || json.data.length === 0) {
                hotelSelect.innerHTML = '<option value="">Không có khách sạn</option>';
                return;
            }

            json.data.forEach(h => {
                let opt = document.createElement('option');
                opt.value = h.hotel_id;
                opt.textContent = h.name + " — " + h.location;
                hotelSelect.appendChild(opt);
            });
        })
        .catch(err => {
            console.error(err);
            hotelSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
            loading.classList.add('d-none');
        });
});
</script>

<?php require_once PATH_ADMIN . "layout/footer.php"; ?>
