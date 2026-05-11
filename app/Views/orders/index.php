<?php require_once 'app/Views/layouts/header.php'; ?>

<style>
    .table-scrollable { overflow-x: auto; overflow-y: hidden;}
    .table-scrollable::-webkit-scrollbar { height: 8px; }
    .table-scrollable::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .table-scrollable::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .table-scrollable::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .table-compact th, .table-compact td { padding: 0.5rem 0.6rem !important; }
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
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted">Tìm kiếm nhanh</label>
            <input type="text" class="form-control form-control-sm" placeholder="Mã đơn, Số Cont, Tên người tạo...">
        </div>
        <div class="col-md-2">
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
        <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" style="height: 31px;">Chờ duyệt (1)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="height: 31px;">Duyệt từ chối (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="height: 31px;">Duyệt đồng ý (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="height: 31px;">Đã thanh toán (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="height: 31px;">Đang làm hàng (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="height: 31px;">Hoàn thành (0)</button>
        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" style="height: 31px;">Chờ dừng đơn (0)</button>
        <button class="btn btn-danger btn-sm rounded-pill px-3 ms-auto d-flex align-items-center" style="height: 31px;"><i class="fas fa-times-circle me-1"></i> Từ chối đơn giá cũ</button>
    </div>

    <div class="table-responsive table-scrollable border rounded">
        <table class="table table-sm table-compact table-hover table-striped text-center align-middle text-nowrap mb-0" style="font-size: 13px;">
            <thead class="table-primary text-white" style="background-color: #0284c7;">
                <tr>
                    <th rowspan="2" class="align-middle border-end">STT</th>
                    <th rowspan="2" class="align-middle border-end">Tác vụ</th>
                    <th rowspan="2" class="align-middle border-end">Mã đơn</th>
                    <th rowspan="2" class="align-middle border-end">Ngày gửi đơn <i class="fas fa-sort text-white-50 ms-1"></i></th>
                    <th rowspan="2" class="align-middle border-end">Người tạo</th>
                    
                    <th rowspan="2" class="align-middle border-end">Ngày duyệt/từ chối <i class="fas fa-sort text-white-50 ms-1"></i></th>
                    <th rowspan="2" class="align-middle border-end">Người duyệt/từ chối</th>
                    <th rowspan="2" class="align-middle border-end">Ngày hoàn thành <i class="fas fa-sort text-white-50 ms-1"></i></th>
                    
                    <th rowspan="2" class="align-middle border-end">Phương án</th>
                    <th rowspan="2" class="align-middle border-end">Depot</th>
                    <th rowspan="2" class="align-middle border-end">Hãng tàu</th>
                    <th rowspan="2" class="align-middle border-end">BL/DO/BKG</th>
                    <th colspan="3" class="border-end text-center">Loại cont</th>
                    <th rowspan="2" class="align-middle">Ghi chú</th>
                </tr>
                <tr>
                    <th class="border-end bg-light text-dark" style="min-width: 45px;">20'</th>
                    <th class="border-end bg-light text-dark" style="min-width: 45px;">40'</th>
                    <th class="border-end bg-light text-dark" style="min-width: 45px;">45'</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($orders)): ?>
                    <?php foreach($orders as $index => $row): ?>
                    <tr>
                        <td class="border-end"><?= $index + 1 ?></td>
                        <td class="border-end text-nowrap">
                          <a href="#" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex justify-content-center align-items-center" title="Sửa" style="width:28px; height:28px;">
                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                          </a>
    
                          <a href="#" class="btn btn-sm btn-outline-danger rounded-circle mx-1 d-inline-flex justify-content-center align-items-center" title="Xóa" style="width:28px; height:28px;" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                            <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                          </a>

                          <a href="#" class="btn btn-sm btn-outline-success rounded-circle d-inline-flex justify-content-center align-items-center" title="Tải xuống" style="width:28px; height:28px;">
                            <i class="fas fa-download" style="font-size: 12px;"></i>
                          </a>
                        </td>
                        <td class="fw-bold text-primary border-end"><?= htmlspecialchars($row['order_code']) ?></td>
                        <td class="border-end"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td class="border-end"><?= htmlspecialchars($row['creator_name']) ?></td>
                        
                        <td class="border-end"></td>
                        <td class="border-end"></td>
                        <td class="border-end"></td>
                        
                        <td class="border-end">
                            <?php if($row['action_type'] == 'Hạ rỗng'): ?>
                                <span class="badge bg-info text-dark">Hạ rỗng</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Cấp rỗng</span>
                            <?php endif; ?>
                        </td>
                        <td class="border-end"><?= htmlspecialchars($row['depot_name']) ?></td>
                        <td class="border-end fw-semibold"><?= htmlspecialchars($row['shipping_line']) ?></td>
                        <td class="border-end"><?= htmlspecialchars($row['bl_do_bkg']) ?></td>
                        
                        <td class="border-end <?= $row['qty_20'] > 0 ? 'text-danger fw-bold' : 'text-muted' ?>"><?= $row['qty_20'] ?></td>
                        <td class="border-end <?= $row['qty_40'] > 0 ? 'text-danger fw-bold' : 'text-muted' ?>"><?= $row['qty_40'] ?></td>
                        <td class="border-end <?= $row['qty_45'] > 0 ? 'text-danger fw-bold' : 'text-muted' ?>"><?= $row['qty_45'] ?></td>
                        
                        <td><?= htmlspecialchars($row['note']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="16" class="text-center py-3">Chưa có dữ liệu</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-3">
        <select class="form-select form-select-sm w-auto">
            <option value="10">10 </option>
            <option value="20">20 </option>
        </select>
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item"><a class="page-link text-primary shadow-sm" href="#">&laquo;</a></li>
            <li class="page-item"><a class="page-link text-primary shadow-sm" href="#">&lsaquo;</a></li>
            <li class="page-item active"><a class="page-link shadow-sm" href="#">1</a></li>
            <li class="page-item"><a class="page-link text-primary shadow-sm" href="#">&rsaquo;</a></li>
            <li class="page-item"><a class="page-link text-primary shadow-sm" href="#">&raquo;</a></li>
        </ul>
    </div>
</div>

<div class="row justify-content-center mb-4">
    <div class="col-md-9">
        <div class="dash-card p-3">
            <div class="table-responsive border rounded">
                <table class="table table-bordered table-sm text-center align-middle mb-0" style="font-size: 13px;">
                    <thead style="background-color: #0284c7; color: white;">
                        <tr>
                            <th class="py-2">SUM</th>
                            <th class="py-2">20'GP</th><th class="py-2">40'GP</th><th class="py-2">40'HC</th>
                            <th class="py-2">20'OT</th><th class="py-2">20'FL</th><th class="py-2">40'FL</th>
                            <th class="py-2">40'OT</th><th class="py-2">45'</th>
                            <th class="py-2 bg-primary text-white">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if(!empty($orders)): ?>
        <?php foreach($orders as $index => $row): ?>
        <tr>
            <td class="border-end"><?= $index + 1 ?></td>
            <td class="border-end">
                <button class="btn btn-sm btn-outline-primary p-1 rounded-circle" title="Sửa" style="width:26px;height:26px;"><i class="fas fa-edit" style="font-size: 11px;"></i></button>
                <button class="btn btn-sm btn-outline-danger p-1 rounded-circle mx-1" title="Xóa" style="width:26px;height:26px;"><i class="fas fa-trash-alt" style="font-size: 11px;"></i></button>
            </td>
            <td class="fw-bold text-primary border-end"><?= htmlspecialchars($row['order_code']) ?></td>
            <td class="border-end"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
            <td class="border-end"><?= htmlspecialchars($row['creator_name']) ?></td>
            
            <td class="border-end"><?= $row['approval_date'] ? date('d/m/Y H:i', strtotime($row['approval_date'])) : '' ?></td>
            <td class="border-end"><?= htmlspecialchars($row['approver_name'] ?? '') ?></td>
            <td class="border-end"><?= $row['completion_date'] ? date('d/m/Y H:i', strtotime($row['completion_date'])) : '' ?></td>
            
            <td class="border-end">
                <span class="badge <?= ($row['action_type'] == 'Hạ rỗng') ? 'bg-info' : 'bg-warning' ?> text-dark">
                    <?= htmlspecialchars($row['action_type']) ?>
                </span>
            </td>
            <td class="border-end"><?= htmlspecialchars($row['depot_name']) ?></td>
            <td class="border-end fw-semibold"><?= htmlspecialchars($row['shipping_line']) ?></td>
            <td class="border-end"><?= htmlspecialchars($row['bl_do_bkg']) ?></td>
            
            <td class="border-end <?= $row['qty_20'] > 0 ? 'text-danger fw-bold' : 'text-muted' ?>"><?= $row['qty_20'] ?></td>
            <td class="border-end <?= $row['qty_40'] > 0 ? 'text-danger fw-bold' : 'text-muted' ?>"><?= $row['qty_40'] ?></td>
            <td class="border-end <?= $row['qty_45'] > 0 ? 'text-danger fw-bold' : 'text-muted' ?>"><?= $row['qty_45'] ?></td>
            
            <td><?= htmlspecialchars($row['note']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="16" class="text-center py-3 text-muted">Chưa có đơn hàng nào trong hệ thống.</td></tr>
    <?php endif; ?>
</tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>