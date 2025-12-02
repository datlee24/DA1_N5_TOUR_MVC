<?php
// views/admin/booking/progress.php
// expects $step (1..5)
?>
<div class="mb-4">
  <div class="d-flex align-items-center gap-3">
    <?php for ($i=1;$i<=5;$i++): 
        $labels = [1=>'Chọn Tour',2=>'Lịch',3=>'HDV',4=>'Khách',5=>'Xác nhận'];
        $active = $step >= $i ? 'bg-primary text-white' : 'bg-light text-muted';
    ?>
      <div class="p-2 rounded <?= $active ?>" style="min-width:130px; text-align:center; font-weight:600;">
        <?= $i ?>. <?= $labels[$i] ?>
      </div>
      <?php if ($i<5): ?>
        <div style="flex:1; height:2px; background:#e9ecef;"></div>
      <?php endif; ?>
    <?php endfor; ?>
  </div>
</div>
