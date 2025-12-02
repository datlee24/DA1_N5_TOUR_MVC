<?php headerAdmin(); ?>
<?php 
$step = 4; 
include __DIR__ . '/progress.php';
?>

<div class="card shadow p-4">
    <h4 class="mb-3">Bước 4: Chọn khách hàng</h4>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-warning"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <input type="text" id="searchCustomer" class="form-control w-50" placeholder="🔍 Tìm khách (Tên, SĐT, Email)...">

        <!-- nút nhanh thêm khách (sử dụng ajaxCreateCustomer của controller nếu cần) -->
        <button class="btn btn-outline-primary" id="btnAddCustomer" type="button">+ Khách mới</button>
    </div>

    <form action="admin.php?act=booking-step4-save" method="POST" id="formStep4">
        <label class="fw-bold mb-2">Danh sách khách (click để chọn)</label>

        <div class="row" id="customersList">
            <?php if (empty($filteredCustomers)): ?>
                <div class="col-12">
                    <div class="alert alert-info">Không còn khách hợp lệ (tất cả đã có lịch/trùng thời gian). Bạn có thể thêm khách mới.</div>
                </div>
            <?php endif; ?>

            <?php foreach ($filteredCustomers as $c): ?>
                <div class="col-md-4 customer-item">
                    <div class="form-check mb-2 p-2 border rounded">
                        <input class="form-check-input" type="checkbox" 
                               name="customer_ids[]" 
                               value="<?= $c['customer_id'] ?>" 
                               id="cus-<?= $c['customer_id'] ?>">
                        <label class="form-check-label" for="cus-<?= $c['customer_id'] ?>">
                            <strong><?= htmlspecialchars($c['fullname']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($c['phone']) ?> — <?= htmlspecialchars($c['email']) ?></small>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="admin.php?act=booking-step3" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-primary">Tiếp tục →</button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>

<!-- Modal thêm khách nhanh -->
<div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formAddCustomer" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm khách mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-2">
              <label class="form-label">Họ tên</label>
              <input type="text" name="fullname" class="form-control" required>
          </div>
          <div class="mb-2">
              <label class="form-label">Số điện thoại</label>
              <input type="text" name="phone" class="form-control">
          </div>
          <div class="mb-2">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control">
          </div>
          <div id="addCustomerAlert"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById("searchCustomer").addEventListener("keyup", function () {
    let keyword = this.value.trim().toLowerCase();
    document.querySelectorAll("#customersList .customer-item").forEach(item => {
        let text = item.innerText.toLowerCase();
        item.style.display = text.includes(keyword) ? "" : "none";
    });
});

// Modal add customer (AJAX)
const btnAdd = document.getElementById('btnAddCustomer');
btnAdd.addEventListener('click', function(){
    var modal = new bootstrap.Modal(document.getElementById('modalAddCustomer'));
    modal.show();
});

document.getElementById('formAddCustomer').addEventListener('submit', function(e){
    e.preventDefault();
    const fd = new FormData(this);
    fetch('admin.php?act=booking-ajax-create-customer', {
        method: 'POST',
        body: fd
    }).then(r => r.json()).then(res => {
        if (res.ok) {
            // append new customer checkbox into list
            const c = res.data;
            const container = document.getElementById('customersList');
            const div = document.createElement('div');
            div.className = 'col-md-4 customer-item';
            div.innerHTML = `
                <div class="form-check mb-2 p-2 border rounded">
                    <input class="form-check-input" type="checkbox" name="customer_ids[]" value="${c.customer_id}" id="cus-${c.customer_id}" checked>
                    <label class="form-check-label" for="cus-${c.customer_id}">
                        <strong>${escapeHtml(c.fullname)}</strong><br>
                        <small class="text-muted">${escapeHtml(c.phone)} — ${escapeHtml(c.email)}</small>
                    </label>
                </div>`;
            container.prepend(div);
            // close modal
            bootstrap.Modal.getInstance(document.getElementById('modalAddCustomer')).hide();
        } else {
            document.getElementById('addCustomerAlert').innerHTML = '<div class="alert alert-danger">'+escapeHtml(res.msg)+'</div>';
        }
    }).catch(err=>{
        document.getElementById('addCustomerAlert').innerHTML = '<div class="alert alert-danger">Lỗi mạng</div>';
    });
});

function escapeHtml(s) {
    if (!s) return '';
    return s.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;');
}
</script>
