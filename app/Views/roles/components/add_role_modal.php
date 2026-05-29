<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark" style="font-size: 16px;"><i class="fas fa-plus-circle text-primary me-2"></i>Thêm Nhóm Quyền Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= $baseUrl ?>/index.php?page=store_role" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Tên nhóm quyền <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Ví dụ: Điều độ viên bãi, Trưởng phòng..." required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Cấp độ Zone tương ứng <span class="text-danger">*</span></label>
                        <select name="level" class="form-select form-select-sm" required>
                            <option value="1">Cấp độ 1: Toàn quyền hệ thống (Admin)</option>
                            <option value="2">Cấp độ 2: Vào tất cả trừ phân quyền (Quản lý)</option>
                            <option value="3">Cấp độ 3: Chỉ Tiếp nhận / Duyệt lệnh (Nhân viên)</option>
                            <option value="4">Cấp độ 4: Chỉ xem Báo cáo & Biểu giá (Kế toán / Giám sát)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Mô tả chi tiết chức năng</label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Ghi rõ giới hạn phân khu của quyền này..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill fw-bold px-4"><i class="fas fa-save me-1"></i> Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
</div>
