<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="dash-card p-4 border-start border-success border-4 h-100 shadow-sm bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2 small fw-bold text-uppercase tracking-wide">Tổng Doanh Thu Dịch Vụ</p>
                    <h2 class="fw-bold text-success mb-0"><?= number_format($totalRevenue, 0, ',', '.') ?> <span class="fs-5 text-muted fw-normal">VNĐ</span></h2>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex justify-content-center align-items-center icon-box-60">
                    <i class="fas fa-money-bill-wave fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="dash-card p-4 border-start border-danger border-4 h-100 shadow-sm bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-2 small fw-bold text-uppercase tracking-wide">Lệnh Đang Chờ Duyệt</p>
                    <h2 class="fw-bold text-danger mb-0"><?= $pendingCount ?> <span class="fs-5 text-muted fw-normal">Yêu cầu</span></h2>
                </div>
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex justify-content-center align-items-center icon-box-60">
                    <i class="fas fa-clock fs-3"></i>
                </div>
            </div>
            <?php if($pendingCount > 0): ?>
                <div class="mt-3 border-top pt-3">
                    <a href="<?= $baseUrl ?>/index.php?page=orders&status=cho_duyet" class="btn btn-sm btn-danger rounded-pill fw-bold px-3">
                        <i class="fas fa-share ms-1"></i> Xử lý ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>