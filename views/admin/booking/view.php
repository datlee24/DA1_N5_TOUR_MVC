<?php headerAdmin(); ?>

<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Chi tiết Booking #<?= htmlspecialchars($booking['booking_id']) ?></h4>
    <a href="admin.php?act=booking" class="btn btn-outline-dark">Quay lại</a>
  </div>

  <div class="row g-3">

    <!-- LEFT -->
    <div class="col-md-6">

      <!-- BOOKING INFO -->
      <div class="card mb-3">
        <div class="card-header">Thông tin booking</div>
        <div class="card-body">
          <p><strong>Tour:</strong> <?= htmlspecialchars($booking['tour_name']) ?></p>
          <p><strong>Ngày:</strong> <?= date("d/m/Y", strtotime($booking['start_date'])) ?> → <?= date("d/m/Y", strtotime($booking['end_date'])) ?></p>
          <p><strong>Số khách:</strong> <?= htmlspecialchars($booking['num_people']) ?></p>
          <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_price']) ?>₫</p>

          <p><strong>Trạng thái:</strong>
            <?php if ($booking['status']=='upcoming') echo '<span class="badge bg-primary">Chưa diễn ra</span>';
              elseif ($booking['status']=='ongoing') echo '<span class="badge bg-warning text-dark">Đang diễn ra</span>';
              elseif ($booking['status']=='completed') echo '<span class="badge bg-success">Kết thúc</span>';
              elseif ($booking['status']=='cancelled') echo '<span class="badge bg-danger">Đã hủy</span>';
            ?>
          </p>

          <!-- PAYMENT UPDATE -->
          <div class="mt-3">
            <label class="form-label">Cập nhật trạng thái thanh toán</label>
            <div class="d-flex gap-2">
              <select id="paymentStatus" class="form-select w-auto">
                <option value="unpaid" <?= $booking['payment_status']=='unpaid'?'selected':'' ?>>Chưa thanh toán</option>
                <option value="deposit" <?= $booking['payment_status']=='deposit'?'selected':'' ?>>Đặt cọc</option>
                <option value="paid" <?= $booking['payment_status']=='paid'?'selected':'' ?>>Đã thanh toán</option>
              </select>
              <button id="btnUpdatePayment" class="btn btn-primary">Cập nhật</button>
            </div>
          </div>

          <p class="mt-3"><strong>Ghi chú:</strong><br><?= nl2br(htmlspecialchars($booking['note'] ?? '')) ?></p>
        </div>
      </div>

      <!-- GUIDE -->
      <div class="card mb-3">
        <div class="card-header">Hướng dẫn viên</div>
        <div class="card-body">
          <?php if ($guide): ?>
            <p><strong>Tên:</strong> <?= htmlspecialchars($guide['fullname']) ?></p>
            <p><strong>CCCD:</strong> <?= htmlspecialchars($guide['cccd'] ?? '-') ?></p>
            <p><strong>SĐT:</strong> <?= htmlspecialchars($guide['phone'] ?? '-') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($guide['email'] ?? '-') ?></p>
            <?php if (!empty($guide['language'])): ?>
              <p><strong>Ngôn ngữ:</strong> <?= htmlspecialchars($guide['language']) ?></p>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-muted">Chưa phân công HDV.</div>
          <?php endif; ?>
        </div>
      </div>

      <!-- DRIVER -->
      <div class="card mb-3">
        <div class="card-header">Tài xế</div>
        <div class="card-body">
          <?php if ($driver): ?>
            <p><strong>Tên:</strong> <?= htmlspecialchars($driver['fullname']) ?></p>
            <p><strong>Loại xe:</strong> <?= htmlspecialchars($driver['vehicle_type'] ?? '-') ?></p>
            <p><strong>Biển số:</strong> <?= htmlspecialchars($driver['license_plate'] ?? '-') ?></p>
            <p><strong>SĐT:</strong> <?= htmlspecialchars($driver['phone'] ?? '-') ?></p>
          <?php else: ?>
            <div class="text-muted">Chưa phân công tài xế.</div>
          <?php endif; ?>
        </div>
      </div>

      <!-- HOTEL -->
      <div class="card mb-3">
        <div class="card-header">Khách sạn</div>
        <div class="card-body">
          <?php if (!empty($assignedHotel)): ?>
            <p><strong>Tên:</strong> <?= htmlspecialchars($assignedHotel['name']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($assignedHotel['address']) ?></p>
            <p><strong>Người đại diện:</strong> <?= htmlspecialchars($assignedHotel['manager_name']) ?></p>
            <p><strong>SĐT đại diện:</strong> <?= htmlspecialchars($assignedHotel['manager_phone']) ?></p>
          <?php else: ?>
            <div class="text-muted">Chưa có khách sạn gán.</div>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <!-- RIGHT: LIST CUSTOMERS -->
    <div class="col-md-6">

      <!-- ADD CUSTOMER -->
      <div class="card mb-3">
        <div class="card-header">Thêm khách vào booking</div>
        <div class="card-body">
          <div class="d-flex gap-2 mb-2">
            <input id="searchAddCustomer" class="form-control" placeholder="Tìm khách để thêm (tên/SĐT/email)">
            <button id="btnAddToBooking" class="btn btn-primary">Thêm</button>
          </div>
          <div class="small text-muted">Gõ tên / SĐT, chọn khách rồi bấm Thêm</div>
          <div id="searchResults" class="mt-2"></div>
        </div>
      </div>

      <!-- CUSTOMER TABLE -->
      <div class="card">
        <div class="card-header">Danh sách khách</div>
        <div class="card-body p-0">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th><th>Họ tên</th><th>SĐT</th><th>Email</th>
                <th>Phòng</th><th>Điểm danh</th>
              </tr>
            </thead>
            <tbody id="customersTableBody">
              <?php $i=1; foreach ($customers as $c): ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($c['fullname']) ?></td>
                  <td><?= htmlspecialchars($c['phone']) ?></td>
                  <td><?= htmlspecialchars($c['email']) ?></td>
                  <td><?= htmlspecialchars($c['room_number'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($c['attendance_status'] ?? 'Chưa') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
/* UPDATE PAYMENT */
document.getElementById('btnUpdatePayment').addEventListener('click', ()=>{
  const ps = document.getElementById('paymentStatus').value;
  const fd = new FormData();
  fd.append('booking_id', '<?= $booking['booking_id'] ?>');
  fd.append('payment_status', ps);

  fetch('admin.php?act=booking-update-payment', {
    method: 'POST', body: fd
  })
  .then(r=>r.json()).then(res=>{
    if (res.ok) { alert('Cập nhật thành công'); location.reload(); }
    else alert('Cập nhật thất bại');
  })
  .catch(()=> alert('Lỗi kết nối'));
});

/* SEARCH CUSTOMER */
let selectedCustomerToAdd = null;

document.getElementById('searchAddCustomer').addEventListener('input', function(){
    const q = this.value.trim();
    const results = document.getElementById('searchResults');

    results.innerHTML = '';
    if (q.length < 2) return;

    // SỬA ĐƯỜNG DẪN TẠI ĐÂY: Dùng 'customer-search' thay vì 'customer'
    fetch('admin.php?act=customer-search&query=' + encodeURIComponent(q)) 
      .then(r=>r.json())
      .then(data=>{
        // ... (phần xử lý kết quả giữ nguyên)
      }).catch(()=> results.innerHTML = '<small>Lỗi</small>');
});

/* ADD CUSTOMER */
document.getElementById('btnAddToBooking').addEventListener('click', function(){
  if (!selectedCustomerToAdd) return alert('Chọn khách trước');

  const fd = new FormData();
  fd.append('schedule_id', '<?= $booking["schedule_id"] ?>');
  fd.append('customer_id', selectedCustomerToAdd);

  fetch('admin.php?act=booking-add-customer', { method:'POST', body: fd })
    .then(r=>r.json())
    .then(res=>{
      if (!res.ok) return alert(res.msg || 'Lỗi');

      const tbody = document.getElementById('customersTableBody');
      tbody.innerHTML = '';

      res.data.forEach((c,i)=>{
        tbody.insertAdjacentHTML('beforeend', `
          <tr>
            <td>${i+1}</td>
            <td>${escapeHtml(c.fullname)}</td>
            <td>${escapeHtml(c.phone)}</td>
            <td>${escapeHtml(c.email||'')}</td>
            <td>${escapeHtml(c.room_number||'-')}</td>
            <td>${escapeHtml(c.attendance_status||'')}</td>
          </tr>
        `);
      });

      alert('Thêm khách thành công');
      document.getElementById('searchResults').innerHTML = '';
      document.getElementById('searchAddCustomer').value = '';
      selectedCustomerToAdd = null;

    })
    .catch(()=> alert('Lỗi kết nối'));
});

function escapeHtml(s){ 
  if(!s) return ''; 
  return s.replaceAll('&','&amp;')
          .replaceAll('<','&lt;')
          .replaceAll('>','&gt;')
          .replaceAll('"','&quot;');
}
</script>

<?php footerAdmin(); ?>
