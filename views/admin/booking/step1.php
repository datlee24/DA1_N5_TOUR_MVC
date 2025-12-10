<?php require_once PATH_ADMIN . "layout/header.php"; ?>

<div class="container-fluid px-4">

    <div class="mt-4 mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold">Bước 1: Chọn Tour & Khách sạn</h2>
        <a href="admin.php?act=booking" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Danh sách Booking
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="admin.php?act=booking-step1-save" id="step1Form" novalidate>

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
                    <div class="invalid-feedback">Vui lòng chọn một tour.</div>
                </div>

                <!-- Chọn khách sạn (bắt buộc) -->
                <div class="mb-3">
                    <label class="fw-semibold mb-1">Chọn Khách Sạn <span class="text-danger">*</span></label>
                    <select name="hotel_id" id="hotelSelect" class="form-select" required>
                        <option value="">-- Chọn khách sạn --</option>
                    </select>
                    <small id="hotelLoading" class="text-muted d-none">Đang tải khách sạn...</small>
                    <div class="invalid-feedback">Vui lòng chọn một khách sạn hợp lệ.</div>
                </div>

                <!-- Chọn tài xế (bắt buộc) -->
                <div class="mb-4">
                    <label class="fw-semibold mb-1">Chọn Tài Xế <span class="text-danger">*</span></label>
                    <select name="driver_id" id="driverSelect" class="form-select" required>
                        <option value="">-- Chọn tài xế --</option>
                        <?php foreach ($drivers as $d): ?>
                            <option value="<?= $d['driver_id'] ?>">
                                <?= htmlspecialchars($d['fullname']) ?> — <?= htmlspecialchars($d['license_plate']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Vui lòng chọn một tài xế.</div>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-lg px-4" id="step1Submit">
                        Tiếp tục <i class="fa fa-arrow-right ms-1"></i>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
    // Khi chọn tour → load khách sạn qua AJAX
    // Khi chọn tour → load khách sạn qua AJAX
    document.getElementById("tourSelect").addEventListener("change", function() {
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
                    // hotel model provides 'address' field, not 'location'
                    opt.textContent = (h.name || '') + " — " + (h.address || '');
                    hotelSelect.appendChild(opt);
                });
            })
            .catch(err => {
                console.error(err);
                hotelSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
                loading.classList.add('d-none');
            });
    });

    // Client-side validation (Bootstrap style) + prevent double submit
    (() => {
        const form = document.getElementById('step1Form');
        const submitBtn = document.getElementById('step1Submit');

        form.addEventListener('submit', function(e) {
            // reset custom validity UI
            const hotelSelect = document.getElementById('hotelSelect');
            const driverSelect = document.getElementById('driverSelect');
            hotelSelect.classList.remove('is-invalid');
            hotelSelect.setCustomValidity('');
            driverSelect.classList.remove('is-invalid');
            driverSelect.setCustomValidity('');

            // Use HTML5 validation
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Ensure selected hotel option exists (loaded via AJAX)
            if (hotelSelect.value) {
                const optExists = Array.from(hotelSelect.options).some(opt => opt.value === hotelSelect.value);
                if (!optExists) {
                    e.preventDefault();
                    e.stopPropagation();
                    hotelSelect.classList.add('is-invalid');
                    hotelSelect.setCustomValidity('invalid');
                    form.classList.add('was-validated');
                    return;
                }
            } else {
                // required attribute will handle empty value, but extra guard
                e.preventDefault();
                e.stopPropagation();
                hotelSelect.classList.add('is-invalid');
                form.classList.add('was-validated');
                return;
            }

            // Ensure selected driver option exists
            if (driverSelect.value) {
                const optExists = Array.from(driverSelect.options).some(opt => opt.value === driverSelect.value);
                if (!optExists) {
                    e.preventDefault();
                    e.stopPropagation();
                    driverSelect.classList.add('is-invalid');
                    driverSelect.setCustomValidity('invalid');
                    form.classList.add('was-validated');
                    return;
                }
            } else {
                e.preventDefault();
                e.stopPropagation();
                driverSelect.classList.add('is-invalid');
                form.classList.add('was-validated');
                return;
            }

            // All client-side checks passed -> disable submit to prevent double submits
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Đang xử lý...';

            // safety re-enable after 10s
            setTimeout(() => {
                if (!submitBtn.disabled) return;
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Tiếp tục <i class="fa fa-arrow-right ms-1"></i>';
            }, 10000);
        });
    })();
</script>

<?php require_once PATH_ADMIN . "layout/footer.php"; ?>