<?php require_once 'app/Views/layouts/header.php'; ?>

<style>
    .table-scrollable { overflow-x: auto; overflow-y: hidden;}
    .table-scrollable::-webkit-scrollbar { height: 7px; }
    .table-scrollable::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .table-scrollable::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .table-compact th, .table-compact td { padding: 0.5rem !important; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-check text-primary me-2"></i>Tiếp nhận đơn hàng</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/index.php?page=home"><i class="fas fa-home text-primary"></i></a></li>
            <li class="breadcrumb-item active">Tiếp nhận đơn</li>
        </ol>
    </nav>
</div>

<div class="dash-card p-3 mb-4">
    <form class="row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Từ ngày: <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control form-control-sm" value="2026-04-11T00:00">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Đến ngày: <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control form-control-sm" value="2026-05-11T23:59">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Tìm kiếm nhanh</label>
            <input type="text" class="form-control form-control-sm" placeholder="Mã đơn, Số Cont, Tên người tạo...">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">Tìm kiếm theo:</label>
            <select class="form-select form-select-sm">
                <option value="all">Tất cả</option>
                <option value="order_code">Mã đơn</option>
                <option value="container_no">Số Container</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-primary btn-sm w-100 fw-bold"><i class="fas fa-search me-1"></i> Tìm kiếm</button>
        </div>
    </form>
</div>

<div class="dash-card p-3 mb-4">
    
    <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
        <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold">Chờ duyệt (1)</button>
        
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">Duyệt từ chối (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">Duyệt đồng ý (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">Đã thanh toán (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">Đang làm hàng (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">Hoàn thành (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">Chờ dừng đơn (0)</button>
        
        <button class="btn btn-danger btn-sm rounded-pill px-3 ms-auto"><i class="fas fa-times-circle me-1"></i> Từ chối đơn giá cũ</button>
    </div>
    <div class="table-responsive table-scrollable border rounded">
        <table class="table table-sm table-compact table-hover table-striped text-center align-middle text-nowrap mb-0" style="font-size: 13px;">
            <thead class="table-primary text-white" style="background-color: #0284c7;">
                <tr>
                    <th rowspan="2" class="align-middle border-end">STT</th>
                    <th rowspan="2" class="align-middle border-end">Tác vụ</th>
                    <th rowspan="2" class="align-middle border-end">Mã đơn</th>
                    <th rowspan="2" class="align-middle border-end">Ngày gửi đơn</th>
                    <th rowspan="2" class="align-middle border-end">Người tạo</th>
                    <th rowspan="2" class="align-middle border-end">Phương án</th>
                    <th rowspan="2" class="align-middle border-end">Depot</th>
                    <th rowspan="2" class="align-middle border-end">Hãng tàu</th>
                    <th rowspan="2" class="align-middle border-end">BL/DO/BKG</th>
                    <th colspan="3" class="border-end text-center">Loại cont</th>
                    <th rowspan="2" class="align-middle">Ghi chú</th>
                </tr>
                <tr>
                    <th class="border-end bg-light text-dark">20'</th>
                    <th class="border-end bg-light text-dark">40'</th>
                    <th class="border-end bg-light text-dark">45'</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-end">1</td>
                    <td class="border-end">
                        <button class="btn btn-sm btn-outline-primary p-1 rounded-circle" title="Sửa" style="width:28px;height:28px;"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger p-1 rounded-circle mx-1" title="Xóa" style="width:28px;height:28px;"><i class="fas fa-trash-alt"></i></button>
                    </td>
                    <td class="fw-bold text-primary border-end">ORD20260507060372</td>
                    <td class="border-end">07/05/2026 13:58</td>
                    <td class="border-end">None</td>
                    <td class="border-end"><span class="badge bg-info text-dark">Hạ rỗng</span></td>
                    <td class="border-end">SINOVNL TÂN VẠN</td>
                    <td class="border-end fw-semibold">COSCO SHIPPING LINE</td>
                    <td class="border-end"></td>
                    <td class="border-end">0</td>
                    <td class="border-end text-danger fw-bold">1</td>
                    <td class="border-end">0</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <select class="form-select form-select-sm w-auto">
            <option value="10">10 / trang</option>
            <option value="20">20 / trang</option>
        </select>
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
        </ul>
    </div>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>