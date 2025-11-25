<div class="steps mb-4">
    <div class="step <?= $step >= 1 ? 'active' : '' ?>">1. Chọn Tour</div>
    <div class="step <?= $step >= 2 ? 'active' : '' ?>">2. Lịch khởi hành</div>
    <div class="step <?= $step >= 3 ? 'active' : '' ?>">3. Hướng dẫn viên</div>
    <div class="step <?= $step >= 4 ? 'active' : '' ?>">4. Khách hàng</div>
    <div class="step <?= $step >= 5 ? 'active' : '' ?>">5. Xác nhận</div>
</div>

<style>
.steps {
    display: flex;
    gap: 10px;
}
.step {
    padding: 8px 16px;
    border-radius: 6px;
    background: #e9ecef;
    font-weight: 500;
}
.step.active {
    background: #0d6efd;
    color: white;
}
</style>
