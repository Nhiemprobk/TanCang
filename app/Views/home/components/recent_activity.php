<div class="col-md-4">
    <div class="dash-card p-4 h-100 shadow-sm bg-white rounded-3">
        <h6 class="fw-bold text-dark mb-4"><i class="fas fa-bell text-warning me-2"></i>Hoạt động mới nhất</h6>
        <div class="notification-list" style="max-height: 400px; overflow-y: auto;">
            <?php if(empty($recentOrders)): ?>
                <p class="text-muted small text-center mt-4">Chưa có hoạt động nào.</p>
            <?php else: ?>
                <?php foreach($recentOrders as $ro): ?>
                    <div class="d-flex border-bottom pb-3 mb-3">
                        <div class="mt-1 me-3">
                            <?php if($ro['status'] == 'Chờ duyệt'): ?>
                                <span class="bg-danger rounded-circle d-inline-block" style="width: 10px; height: 10px;"></span>
                            <?php elseif($ro['status'] == 'Hoàn thành' || $ro['status'] == 'Đã thanh toán'): ?>
                                <span class="bg-success rounded-circle d-inline-block" style="width: 10px; height: 10px;"></span>
                            <?php else: ?>
                                <span class="bg-info rounded-circle d-inline-block" style="width: 10px; height: 10px;"></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="mb-1 small fw-bold text-dark"><?= htmlspecialchars($ro['creator_name']) ?></p>
                            <p class="mb-1 small text-muted">
                                Mã lệnh: <span class="fw-semibold text-primary"><?= htmlspecialchars($ro['order_code']) ?></span>
                            </p>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <span class="badge bg-light text-dark border"><?= $ro['status'] ?></span>
                                <span style="font-size: 11px; color: #888;"><i class="fas fa-clock me-1"></i><?= date('d/m H:i', strtotime($ro['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mt-3 pt-2 border-top">
            <a href="<?= $baseUrl ?>/index.php?page=orders" class="text-decoration-none small fw-bold">Xem toàn bộ Lệnh <i class="fas fa-angle-right ms-1"></i></a>
        </div>
    </div>
</div>
