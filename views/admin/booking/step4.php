<?php headerAdmin(); $step = 4; include __DIR__ . '/progress.php'; ?>

<div class="card shadow-sm p-4">
  <h4>Bước 4 — Chọn Khách hàng</h4>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-warning"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <input id="searchCustomer" class="form-control w-50" placeholder="Tìm khách (tên / SĐT / email)">
    <button id="btnAddCustomer" type="button" class="btn btn-outline-primary">+ Thêm khách nhanh</button>
  </div>

  <form id="formStep4" action="admin.php?act=booking-step4-save" method="POST">
    <div class="row g-3" id="customersList">
      <?php if (empty($filteredCustomers)): ?>
        <div class="col-12">
          <div class="alert alert-info">Không còn khách hợp lệ. Vui lòng thêm khách mới.</div>
        </div>
      <?php endif; ?>

      <?php foreach ($filteredCustomers as $c): ?>
        <div class="col-md-4 customer-item">
          <div class="p-3 border rounded">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="customer_ids[]" value="<?= $c['customer_id'] ?>" id="cus-<?= $c['customer_id'] ?>">
              <label class="form-check-label" for="cus-<?= $c['customer_id'] ?>">
                <strong><?= htmlspecialchars($c['fullname']) ?></strong><br>
                <small class="text-muted"><?= htmlspecialchars($c['phone']) ?> — <?= htmlspecialchars($c['email']) ?></small>
              </label>
            </div>
            <?php if (!empty($c['notes'])): ?>
              <div class="small mt-2 text-muted">Ghi chú: <?= htmlspecialchars($c['notes']) ?></div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-3 d-flex justify-content-between">
      <a href="admin.php?act=booking-step3" class="btn btn-outline-secondary">← Quay lại</a>
      <button class="btn btn-primary">Tiếp tục →</button>
    </div>
  </form>
</div>

<?php footerAdmin(); ?>

<!-- Modal: Thêm khách nhanh -->
<div class="modal fade" id="modalAddCustomer" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formAddCustomer" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm khách nhanh</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="addCustomerAlert"></div>
        <div class="mb-2">
          <label class="form-label">Họ tên</label>
          <input name="fullname" class="form-control" required>
        </div>
        <div class="mb-2">
          <label class="form-label">SĐT</label>
          <input name="phone" class="form-control">
        </div>
        <div class="mb-2">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Đóng</button>
        <button class="btn btn-primary" type="submit">Lưu</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('searchCustomer').addEventListener('input', function(){
  const q = this.value.trim().toLowerCase();
  document.querySelectorAll('#customersList .customer-item').forEach(el=>{
    el.style.display = el.innerText.toLowerCase().includes(q) ? '' : 'none';
  });
});

// Modal add customer
const modal = new bootstrap.Modal(document.getElementById('modalAddCustomer'));
document.getElementById('btnAddCustomer').addEventListener('click', ()=> modal.show());

document.getElementById('formAddCustomer').addEventListener('submit', function(e){
  e.preventDefault();
  const fd = new FormData(this);
  fetch('admin.php?act=ajax-customer-create', { method:'POST', body: fd })
    .then(r=>r.json()).then(res=>{
      if (res.ok) {
        // prepend new customer card and check it
        const c = res.data;
        const container = document.getElementById('customersList');
        const div = document.createElement('div');
        div.className = 'col-md-4 customer-item';
        div.innerHTML = `
          <div class="p-3 border rounded">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="customer_ids[]" value="${c.customer_id}" id="cus-${c.customer_id}" checked>
              <label class="form-check-label" for="cus-${c.customer_id}">
                <strong>${escapeHtml(c.fullname)}</strong><br>
                <small class="text-muted">${escapeHtml(c.phone)} — ${escapeHtml(c.email)}</small>
              </label>
            </div>
          </div>`;
        container.prepend(div);
        modal.hide();
        this.reset();
      } else {
        document.getElementById('addCustomerAlert').innerHTML = `<div class="alert alert-danger">${escapeHtml(res.msg||'Lỗi')}</div>`;
      }
    }).catch(()=> {
      document.getElementById('addCustomerAlert').innerHTML = `<div class="alert alert-danger">Lỗi kết nối</div>`;
    });
});

function escapeHtml(s){ if(!s) return ''; return s.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;'); }
</script>
