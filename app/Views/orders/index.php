<!-- NHÚNG HEADER -->
<?php require_once '../app/Views/layouts/header.php'; ?>

<!-- BREADCRUMB (Điều hướng) -->
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active" aria-current="page">Tiếp nhận đơn hàng</li>
  </ol>
</nav>

<!-- KHUNG TÌM KIẾM (FILTER) -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form class="row g-3">
            <div class="col-md-3">
                <label class="form-label text-muted small">Từ ngày: <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Đến ngày: <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control form-control-sm">
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Tìm kiếm:</label>
                <input type="text" class="form-control form-control-sm" placeholder="Nhập mã đơn, mã container...">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i> Tìm kiếm</button>
            </div>
        </form>
    </div>
</div>

<!-- BẢNG DỮ LIỆU ĐƠN HÀNG -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Các nút thao tác -->
        <div class="mb-3">
            <button class="btn btn-warning text-white btn-sm rounded-pill px-3 me-1">Chờ duyệt (1)</button>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-1">Đã thanh toán (0)</button>
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">Hoàn thành (0)</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle text-nowrap">
                <thead class="table-primary" style="background-color: #0b3a82; color: white;">
                    <tr>
                        <th>STT</th>
                        <th>Tác vụ</th>
                        <th>Mã đơn</th>
                        <th>Ngày gửi đơn</th>
                        <th>Người tạo</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info rounded-circle"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-outline-success rounded-circle"><i class="fas fa-check"></i></button>
                            <button class="btn btn-sm btn-outline-danger rounded-circle"><i class="fas fa-times"></i></button>
                        </td>
                        <td class="text-primary fw-bold">ORD20260507060372</td>
                        <td>07/05/2026 13:58</td>
                        <td>Nhà xe A</td>
                        <td><span class="badge bg-warning text-dark">Chờ duyệt</span></td>
                    </tr>
                    <!-- Các dòng dữ liệu PHP foreach sẽ lặp ở đây -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- NHÚNG FOOTER -->
<?php require_once '../app/Views/layouts/footer.php'; ?>