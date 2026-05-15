<?php 
/**
 * @var array $users
 * @var array $stats
 */
require_once 'app/Views/layouts/header.php'; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-user-shield text-primary me-2"></i>Quản trị hệ thống tài khoản</h4>
        <p class="text-muted small mb-0">Cấp quyền và giám sát hoạt động của nhân viên & đối khách hàng</p>
    </div>
    <button class="btn btn-primary shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus-circle me-1"></i> Tạo tài khoản mới
    </button>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="dash-card p-3 border-start border-primary border-5 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                    <i class="fas fa-users fs-4"></i>
                </div>
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
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                    <i class="fas fa-user-check fs-4"></i>
                </div>
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
                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle me-3">
                    <i class="fas fa-user-lock fs-4"></i>
                </div>
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
                <?php foreach($users as $user): ?>
                <tr>
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['full_name']) ?>&background=random&color=fff&bold=true" 
                                 class="rounded-circle me-3 shadow-sm" width="45" height="45">
                            <div>
                                <div class="fw-bold text-dark mb-0"><?= htmlspecialchars($user['full_name']) ?></div>
                                <div class="text-muted small">@<?= htmlspecialchars($user['username']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php 
                            $roleClass = 'bg-secondary';
                            if($user['role_id'] == 1) $roleClass = 'bg-danger'; // Admin
                            if($user['role_id'] == 2) $roleClass = 'bg-primary'; // Manager
                            if($user['role_id'] == 4) $roleClass = 'bg-info'; // Nhà xe
                        ?>
                        <span class="badge <?= $roleClass ?> bg-opacity-10 text-<?= str_replace('bg-', '', $roleClass) ?> rounded-pill px-3 border border-<?= str_replace('bg-', '', $roleClass) ?> border-opacity-25">
                            <?= $user['role_name'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="small mb-1"><i class="far fa-envelope me-2 text-muted"></i><?= $user['email'] ?></div>
                        <div class="small"><i class="fas fa-phone-alt me-2 text-muted"></i><?= $user['phone'] ?></div>
                    </td>
                    <td>
                        <?php if($user['is_active'] == 1): ?>
                            <span class="d-flex align-items-center text-success fw-bold small">
                                <span class="p-1 bg-success rounded-circle me-2 ripple-success"></span> Hoạt động
                            </span>
                        <?php else: ?>
                            <span class="d-flex align-items-center text-danger fw-bold small">
                                <span class="p-1 bg-danger rounded-circle me-2"></span> Đã khóa
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle border shadow-sm" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-edit me-2 text-primary"></i> Chỉnh sửa hồ sơ</a></li>
                                <li><a class="dropdown-item py-2" href="#"><i class="fas fa-key me-2 text-warning"></i> Cấp lại mật khẩu</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php if($user['is_active'] == 1): ?>
                                    <li><a class="dropdown-item py-2 text-danger" href="#"><i class="fas fa-user-slash me-2"></i> Khóa tài khoản</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item py-2 text-success" href="#"><i class="fas fa-user-check me-2"></i> Kích hoạt lại</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item py-2 text-danger" href="#" onclick="return confirm('Xác nhận xóa vĩnh viễn?')"><i class="fas fa-trash-alt me-2"></i> Xóa vĩnh viễn</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Hiệu ứng chấm xanh nhấp nháy cho tài khoản online */
    .ripple-success {
        box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4);
        animation: ripple 1.5s infinite;
    }
    @keyframes ripple {
        0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
        100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc !important;
        transition: 0.2s;
    }
</style>

<?php require_once 'app/Views/layouts/footer.php'; ?>