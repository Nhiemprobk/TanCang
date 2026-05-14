<?php require_once 'app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-edit text-primary me-2"></i>Cập nhật Đơn hàng</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php?page=home"><i class="fas fa-home text-primary"></i></a></li>
            <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php?page=orders">Tiếp nhận đơn</a></li>
            <li class="breadcrumb-item active">Sửa đơn</li>
        </ol>
    </nav>
</div>

<div class="dash-card p-4">
    <!-- Form bắt đầu: action="" nghĩa là gửi data về chính trang hiện tại -->
    <form action="<?= $baseUrl ?>/index.php?page=edit_order&id=<?= $order['id'] ?>" method="POST">
        <div class="row g-3">
            <!-- Mã đơn hàng (Không cho sửa mã để tránh lỗi trùng lặp) -->
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Mã đơn hàng</label>
                <input type="text" name="order_code" class="form-control" value="<?= htmlspecialchars($order['order_code']) ?>" readonly style="background-color: #f8fafc;">
            </div>

            <!-- Người tạo -->
            <div class="col-md-6">
                <label class="form-label fw-bold small text-muted">Người tạo</label>
                <input type="text" name="creator_name" class="form-control" value="<?= htmlspecialchars($order['creator_name']) ?>" required>
            </div>

            <!-- Phương án (Dropdown) -->
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Phương án</label>
                <select name="action_type" class="form-select">
                    <option value="Hạ rỗng" <?= ($order['action_type'] == 'Hạ rỗng') ? 'selected' : '' ?>>Hạ rỗng</option>
                    <option value="Cấp rỗng" <?= ($order['action_type'] == 'Cấp rỗng') ? 'selected' : '' ?>>Cấp rỗng</option>
                </select>
            </div>

            <!-- Depot -->
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Depot</label>
                <input type="text" name="depot_name" class="form-control" value="<?= htmlspecialchars($order['depot_name']) ?>" required>
            </div>

            <!-- Hãng tàu -->
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Hãng tàu</label>
                <input type="text" name="shipping_line" class="form-control" value="<?= htmlspecialchars($order['shipping_line']) ?>" required>
            </div>

            <!-- Số lượng Cont 20, 40, 45 -->
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Loại Cont 20'</label>
                <input type="number" name="qty_20" class="form-control" min="0" value="<?= $order['qty_20'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Loại Cont 40'</label>
                <input type="number" name="qty_40" class="form-control" min="0" value="<?= $order['qty_40'] ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold small text-muted">Loại Cont 45'</label>
                <input type="number" name="qty_45" class="form-control" min="0" value="<?= $order['qty_45'] ?>">
            </div>

            <!-- Ghi chú -->
            <div class="col-md-12">
                <label class="form-label fw-bold small text-muted">Ghi chú</label>
                <textarea name="note" class="form-control" rows="2"><?= htmlspecialchars($order['note'] ?? '') ?></textarea>
            </div>
        </div>

        <hr class="my-4">
        
        <!-- Các nút bấm -->
        <div class="d-flex justify-content-end gap-2">
            <a href="<?= $baseUrl ?>/index.php?page=orders" class="btn btn-outline-secondary px-4">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-save me-1"></i> Lưu thay đổi</button>
        </div>
    </form>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>