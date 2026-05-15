<?php require_once 'app/Views/layouts/header.php'; ?>

<?php
// Khởi tạo giá trị mặc định nếu view bị gọi sai cách hoặc bị thiếu biến truyền vào
if (!isset($stats)) {
    $stats = ['total' => 0, 'active' => 0, 'locked' => 0];
}
if (!isset($users)) {
    $users = [];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-user-shield text-primary me-2"></i>Quản trị hệ thống tài khoản</h4>
        <p class="text-muted small mb-0 mt-1">Cấp quyền và giám sát hoạt động của nhân viên & khách hàng</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus-circle me-1"></i> Tạo tài khoản mới
    </button>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="dash-card p-3 border-start border-primary border-5 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3"><i class="fas fa-users fs-4"></i></div>
                <div>
                    <h5 class="fw-bold mb-0"><?= $stats['total'] ?></h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tổng số thành viên</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dash-card p-3 border-start border-success border-5 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3"><i class="fas fa-user-check fs-4"></i></div>
                <div>
                    <h5 class="fw-bold mb-0"><?= $stats['active'] ?></h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Đang hoạt động</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="dash-card p-3 border-start border-danger border-5 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle me-3"><i class="fas fa-user-lock fs-4"></i></div>
                <div>
                    <h5 class="fw-bold mb-0 text-danger"><?= $stats['locked'] ?></h5>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 11px;">Tài khoản bị khóa</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dash-card border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-muted fw-bold" style="font-size: 13px;">THÔNG TIN NGƯỜI DÙNG</th>
                    <th class="py-3 text-muted fw-bold" style="font-size: 13px;">VAI TRÒ / QUYỀN</th>
                    <th class="py-3 text-muted fw-bold" style="font-size: 13px;">LIÊN HỆ</th>
                    <th class="py-3 text-muted fw-bold" style="font-size: 13px;">TRẠNG THÁI</th>
                    <th class="py-3 text-center text-muted fw-bold" style="font-size: 13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($users)): ?>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=random&color=fff&bold=true" class="rounded-circle me-3 shadow-sm" width="45" height="45">
                                <div>
                                    <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($user['full_name']) ?></div>
                                    <div class="text-muted small">@<?= htmlspecialchars($user['username']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php 
                                $roleClass = 'bg-secondary';
                                if($user['role_id'] == 1) $roleClass = 'bg-danger';
                                if($user['role_id'] == 2) $roleClass = 'bg-primary';
                                if($user['role_id'] == 4) $roleClass = 'bg-info';
                            ?>
                            <span class="badge <?= $roleClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $roleClass) ?> rounded-pill px-3 border border-<?= str_replace('bg-', '', $roleClass) ?> border-opacity-25">
                                <i class="fas fa-shield-alt me-1"></i> <?= htmlspecialchars($user['role_name'] ?? 'Chưa cấp quyền') ?>
                            </span>
                        </td>
                        <td>
                            <div class="small mb-1"><i class="far fa-envelope me-2 text-muted"></i><?= htmlspecialchars($user['email'] ?? 'Chưa cập nhật') ?></div>
                            <div class="small"><i class="fas fa-phone-alt me-2 text-muted"></i><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></div>
                        </td>
                        <td>
                            <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                                <span class="d-flex align-items-center text-success fw-bold small"><span class="p-1 bg-success rounded-circle me-2 ripple-success"></span> Hoạt động</span>
                            <?php else: ?>
                                <span class="d-flex align-items-center text-danger fw-bold small"><span class="p-1 bg-danger rounded-circle me-2"></span> Đã khóa</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle border shadow-sm" data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                                           data-id="<?= $user['id'] ?>" data-fullname="<?= htmlspecialchars($user['full_name']) ?>" 
                                           data-email="<?= htmlspecialchars($user['email']) ?>" data-phone="<?= htmlspecialchars($user['phone']) ?>" data-role="<?= $user['role_id'] ?>" onclick="fillEditModal(this)">
                                        <i class="fas fa-user-edit me-2 text-primary"></i> Chỉnh sửa hồ sơ
                                    </a></li>
                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#resetPassModal" 
                                           data-id="<?= $user['id'] ?>" data-username="<?= htmlspecialchars($user['username']) ?>" onclick="fillResetModal(this)">
                                        <i class="fas fa-key me-2 text-warning"></i> Cấp lại mật khẩu
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <?php if(isset($user['is_active']) && $user['is_active'] == 1): ?>
                                        <li><a class="dropdown-item py-2 text-danger" href="<?= $baseUrl ?>/index.php?page=users&action=toggle_status&id=<?= $user['id'] ?>"><i class="fas fa-user-slash me-2"></i> Khóa tài khoản</a></li>
                                    <?php else: ?>
                                        <li><a class="dropdown-item py-2 text-success" href="<?= $baseUrl ?>/index.php?page=users&action=toggle_status&id=<?= $user['id'] ?>"><i class="fas fa-user-check me-2"></i> Mở khóa tài khoản</a></li>
                                    <?php endif; ?>
                                    <li><a class="dropdown-item py-2 text-danger" href="<?= $baseUrl ?>/index.php?page=users&action=delete&id=<?= $user['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa VĨNH VIỄN tài khoản này không? Hành động này không thể hoàn tác!')"><i class="fas fa-trash-alt me-2"></i> Xóa vĩnh viễn</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">Không có dữ liệu người dùng</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: #0284c7;">
                <h6 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i>Tạo tài khoản hệ thống</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= $baseUrl ?>/index.php?page=users" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Cấp quyền <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select" required>
                                <option value="" disabled selected>-- Chọn nhóm quyền --</option>
                                <option value="1">Quản trị viên (Admin)</option>
                                <option value="2">Nhân viên Điều độ</option>
                                <option value="3">Thương vụ</option>
                                <option value="4">Khách hàng / Nhà xe</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fas fa-check me-1"></i> Xác nhận tạo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h6 class="modal-title fw-bold text-primary"><i class="fas fa-user-edit me-2"></i>Chỉnh sửa thông tin</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= $baseUrl ?>/index.php?page=users" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id"> <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Họ và tên</label>
                            <input type="text" name="full_name" id="edit_full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Số điện thoại</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold">Nhóm quyền</label>
                            <select name="role_id" id="edit_role_id" class="form-select" required>
                                <option value="1">Quản trị viên (Admin)</option>
                                <option value="2">Nhân viên Điều độ</option>
                                <option value="3">Thương vụ</option>
                                <option value="4">Khách hàng / Nhà xe</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fas fa-save me-1"></i> Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="resetPassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h6 class="modal-title fw-bold"><i class="fas fa-key me-2"></i>Cấp lại mật khẩu</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= $baseUrl ?>/index.php?page=users" method="POST">
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="id" id="reset_id">
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Tài khoản: <strong class="text-dark" id="reset_username"></strong></p>
                    <label class="form-label small fw-bold">Nhập mật khẩu mới <span class="text-danger">*</span></label>
                    <input type="text" name="new_password" class="form-control text-center fw-bold fs-5" required placeholder="••••••••">
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning rounded-pill fw-bold"><i class="fas fa-check me-1"></i> Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Hàm ném dữ liệu từ nút bấm vào Modal Sửa
    function fillEditModal(el) {
        document.getElementById('edit_id').value = el.getAttribute('data-id');
        document.getElementById('edit_full_name').value = el.getAttribute('data-fullname');
        document.getElementById('edit_email').value = el.getAttribute('data-email');
        document.getElementById('edit_phone').value = el.getAttribute('data-phone');
        document.getElementById('edit_role_id').value = el.getAttribute('data-role');
    }

    // Hàm ném dữ liệu từ nút bấm vào Modal Đổi mật khẩu
    function fillResetModal(el) {
        document.getElementById('reset_id').value = el.getAttribute('data-id');
        document.getElementById('reset_username').innerText = '@' + el.getAttribute('data-username');
    }
</script>

<?php if(isset($_GET['msg'])): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const msgs = {
            'added': '🎉 Tạo tài khoản thành công!',
            'edited': '✅ Cập nhật thông tin thành công!',
            'password_reset': '🔑 Đã cấp lại mật khẩu mới thành công!',
            'status_changed': '🔄 Đã thay đổi trạng thái tài khoản!',
            'deleted': '🗑️ Đã xóa tài khoản vĩnh viễn!'
        };
        const msgType = '<?= $_GET['msg'] ?>';
        if(msgs[msgType]) alert(msgs[msgType]);
        // Dọn dẹp URL cho gọn
        window.history.pushState({}, document.title, window.location.pathname + "?page=users");
    });
</script>
<?php endif; ?>
<?php if(isset($error_msg)): ?>
    <script>alert("❌ <?= $error_msg ?>");</script>
<?php endif; ?>

<style>
    .ripple-success { box-shadow: 0 0 0 0 rgba(25,135,84,0.4); animation: ripple 1.5s infinite; }
    @keyframes ripple { 0% { box-shadow: 0 0 0 0 rgba(25,135,84,0.4); } 70% { box-shadow: 0 0 0 8px rgba(25,135,84,0); } 100% { box-shadow: 0 0 0 0 rgba(25,135,84,0); } }
    .table-hover tbody tr:hover { background-color: #f8fafc !important; transition: all 0.2s ease; }
</style>

<?php require_once 'app/Views/layouts/footer.php'; ?>