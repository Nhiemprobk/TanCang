<?php require_once 'app/Views/layouts/header.php'; ?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php?page=home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Hồ sơ cá nhân</li>
  </ol>
</nav>

<div class="card shadow-sm border-0 mb-4" style="border-radius: 20px; overflow: hidden;">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
        <h5 class="fw-bold text-primary mb-0" style="color: #0284c7 !important;">
            <i class="fas fa-id-card me-2"></i> Thông tin người dùng
        </h5>
    </div>
    
    <div class="card-body px-4 pb-4 pt-2">
        <div class="text-center mb-4 pb-3 border-bottom border-light">
            <img src="https://ui-avatars.com/api/?name=Admin&background=0284c7&color=fff&size=120" 
                 alt="Avatar" 
                 class="rounded-circle mb-3 shadow-sm" 
                 style="border: 5px solid #e0f2fe;">
            <h4 class="fw-bold mb-1 text-dark">Quản trị viên</h4>
            <p class="text-muted mb-0">admin@logisport.vn</p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <tbody>
                    <tr>
                        <th class="text-muted fw-semibold w-25 border-light py-3">Họ và tên</th>
                        <td class="border-light py-3 text-dark fw-medium">Quản trị viên (Admin)</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Chức vụ</th>
                        <td class="border-light py-3 text-dark">Nhân viên chính thức</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Vị trí công việc</th>
                        <td class="border-light py-3 text-dark">Quản trị hệ thống LOGISPORT</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Phòng ban</th>
                        <td class="border-light py-3 text-dark">Ban CNTT & Điều hành Cảng</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Địa chỉ công tác</th>
                        <td class="border-light py-3 text-dark">Cảng Tân Cảng, TP. Hồ Chí Minh</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Trạng thái nhân viên</th>
                        <td class="border-light py-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">
                                <i class="fas fa-circle text-primary me-1" style="font-size: 8px;"></i> Đang làm việc
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Email</th>
                        <td class="border-light py-3 text-dark">admin@logisport.vn</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Số điện thoại</th>
                        <td class="border-light py-3 text-dark">0987.654.321</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-light py-3">Ngày sinh</th>
                        <td class="border-light py-3 text-dark">01/01/1990</td>
                    </tr>
                    <tr>
                        <th class="text-muted fw-semibold border-0 py-3">Giới tính</th>
                        <td class="border-0 py-3 text-dark">Nam</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>