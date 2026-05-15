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
    <form action="index.php" method="GET" class="row g-2 align-items-end">
        
        <input type="hidden" name="page" value="orders">

        <input type="hidden" name="limit" value="<?= $limit ?>">
        
        <?php if(isset($_GET['status'])): ?>
            <input type="hidden" name="status" value="<?= htmlspecialchars($_GET['status']) ?>">
        <?php endif; ?>

        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Từ ngày: </label>
            <input type="date" name="from_date" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['from_date'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Đến ngày: </label>
            <input type="date" name="to_date" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['to_date'] ?? '') ?>">
        </div>
        
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted">Tìm kiếm nhanh</label>
            <input type="text" name="search_text" class="form-control form-control-sm" placeholder="Mã đơn, Tên người tạo, số BL..." value="<?= htmlspecialchars($_GET['search_text'] ?? '') ?>">
        </div>
        
        <div class="col-md-2">
            <label class="form-label small fw-bold text-muted">Tìm kiếm theo:</label>
            <select name="search_type" class="form-select form-select-sm">
                <option value="all" <?= (isset($_GET['search_type']) && $_GET['search_type'] == 'all') ? 'selected' : '' ?>>Tất cả</option>
                <option value="order_code" <?= (isset($_GET['search_type']) && $_GET['search_type'] == 'order_code') ? 'selected' : '' ?>>Mã đơn</option>
                <option value="creator_name" <?= (isset($_GET['search_type']) && $_GET['search_type'] == 'creator_name') ? 'selected' : '' ?>>Người tạo</option>
            </select>
        </div>
        
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold"><i class="fas fa-search me-1"></i> Tìm kiếm</button>
        </div>
    </form>
</div>

<div class="dash-card p-3 mb-4">
    
    <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
    <?php $stt = $_GET['status'] ?? 'all'; ?>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=all&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= ($stt == 'all' && empty($_GET['search_text'])) ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Tất cả</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=cho_duyet&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'cho_duyet' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Chờ duyệt (<?= $counts['Chờ duyệt'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=tu_choi&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'tu_choi' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Duyệt từ chối (<?= $counts['Duyệt từ chối'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=dong_y&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'dong_y' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Duyệt đồng ý (<?= $counts['Duyệt đồng ý'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=da_thanh_toan&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'da_thanh_toan' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Đã thanh toán (<?= $counts['Đã thanh toán'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=dang_lam_hang&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'dang_lam_hang' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Đang làm hàng (<?= $counts['Đang làm hàng'] ?? 0 ?>)</a>
    
    <a href="<?= $baseUrl ?>/index.php?page=orders&status=hoan_thanh&limit=<?= $limit ?>" 
       class="btn btn-sm rounded-pill px-3 <?= $stt == 'hoan_thanh' ? 'btn-primary fw-bold' : 'btn-outline-secondary' ?>" 
       style="height: 31px; display: inline-flex; align-items: center;">Hoàn thành (<?= $counts['Hoàn thành'] ?? 0 ?>)</a>

    <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 ms-auto d-flex align-items-center" style="height: 31px;" data-bs-toggle="modal" data-bs-target="#rejectOldPriceModal">
       <i class="fas fa-times-circle me-1"></i> Từ chối đơn giá cũ
    </button>
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
                          <a href="<?= $baseUrl ?>/index.php?page=edit_order&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex justify-content-center align-items-center" title="Sửa" style="width:28px; height:28px;">
                            <i class="fas fa-edit" style="font-size: 12px;"></i>
                          </a>
    
                          <a href="<?= $baseUrl ?>/index.php?page=delete_order&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger rounded-circle mx-1 d-inline-flex justify-content-center align-items-center" title="Xóa" style="width:28px; height:28px;" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                            <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                          </a>

                          <a href="<?= $baseUrl ?>/index.php?page=download_order&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success rounded-circle d-inline-flex justify-content-center align-items-center" title="Tải xuống" style="width:28px; height:28px;">
                            <i class="fas fa-download" style="font-size: 12px;"></i>
                          </a>
                        </td>
                        <td class="fw-bold text-primary border-end"><?= htmlspecialchars($row['order_code']) ?></td>
                        <td class="border-end"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                        <td class="border-end"><?= htmlspecialchars($row['creator_name']) ?></td>
                        
                        <td class="border-end"><?= !empty($row['approval_date']) ? date('d/m/Y H:i', strtotime($row['approval_date'])) : '' ?></td>
                        <td class="border-end"><?= htmlspecialchars($row['approver_name'] ?? '') ?></td>
                        <td class="border-end"><?= !empty($row['completion_date']) ? date('d/m/Y H:i', strtotime($row['completion_date'])) : '' ?></td>
                        
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
        <?php 
            // Tạo query string để giữ lại các bộ lọc hiện tại
            $query_params = $_GET; 
            unset($query_params['p']); // Xóa trang cũ để gán trang mới
            $base_query = http_build_query($query_params);
        ?>
        
        <div class="d-flex align-items-center gap-2">
            <span class="small text-muted">Hiển thị:</span>
            <select class="form-select form-select-sm w-auto" onchange="location.href='index.php?<?= $base_query ?>&limit=' + this.value + '&p=1'">
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10 dòng</option>
                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20 dòng</option>
            </select>
            <span class="small text-muted">/ Tổng <?= $total_records ?> đơn</span>
        </div>

        <ul class="pagination pagination-sm mb-0">
            <li class="page-item <?= $p <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?<?= $base_query ?>&p=1">&laquo;</a>
            </li>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $p == $i ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?<?= $base_query ?>&p=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <li class="page-item <?= $p >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?<?= $base_query ?>&p=<?= $total_pages ?>">&raquo;</a>
            </li>
        </ul>
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
                        <tr>
                            <td class="fw-bold bg-light">Hạ rỗng</td>
                            <td><?= $summary['ha_rong']['20'] ?></td>
                            <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                            <td class="<?= $summary['ha_rong']['40'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['ha_rong']['40'] ?></td>
                            <td class="<?= $summary['ha_rong']['45'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['ha_rong']['45'] ?></td>
                            <td class="fw-bold text-primary"><?= $summary['ha_rong']['tong'] ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold bg-light">Cấp rỗng</td>
                            <td class="<?= $summary['cap_rong']['20'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['cap_rong']['20'] ?></td>
                            <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                            <td class="<?= $summary['cap_rong']['40'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['cap_rong']['40'] ?></td>
                            <td class="<?= $summary['cap_rong']['45'] > 0 ? 'text-danger fw-bold' : '' ?>"><?= $summary['cap_rong']['45'] ?></td>
                            <td class="fw-bold text-primary"><?= $summary['cap_rong']['tong'] ?></td>
                        </tr>
                        <tr class="table-secondary">
                            <td class="fw-bold">Tổng (CONT)</td>
                            <?php 
                                $tong20 = $summary['ha_rong']['20'] + $summary['cap_rong']['20'];
                                $tong40 = $summary['ha_rong']['40'] + $summary['cap_rong']['40'];
                                $tong45 = $summary['ha_rong']['45'] + $summary['cap_rong']['45'];
                                $tongAll = $summary['ha_rong']['tong'] + $summary['cap_rong']['tong'];
                            ?>
                            <td class="fw-bold <?= $tong20 > 0 ? 'text-danger' : '' ?>"><?= $tong20 ?></td>
                            <td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>
                            <td class="fw-bold <?= $tong40 > 0 ? 'text-danger' : '' ?>"><?= $tong40 ?></td>
                            <td class="fw-bold <?= $tong45 > 0 ? 'text-danger' : '' ?>"><?= $tong45 ?></td>
                            <td class="fw-bold text-danger fs-6"><?= $tongAll ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusBtns = document.querySelectorAll('.filter-status');
    statusBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            statusBtns.forEach(b => {
                b.classList.remove('btn-primary', 'fw-bold');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary', 'fw-bold');
        });
    });
});
</script>

<div class="modal fade" id="rejectOldPriceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <form action="<?= $baseUrl ?>/index.php?page=reject_old_price" method="POST">
                
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4 position-relative">
                    <div class="d-flex align-items-center mb-2">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 48px; height: 48px;">
                            <i class="fas fa-exclamation-triangle fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0">Từ chối đơn hàng giá cũ</h5>
                            <p class="text-muted small mb-0">Hành động này áp dụng cho toàn bộ đơn đang chờ.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0 end-0 mt-4 me-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 pt-3 pb-4">
                    <div class="bg-light rounded p-3 mb-3 border" style="border-left: 4px solid #f59e0b !important;">
                        <span class="small text-dark fw-medium"><i class="fas fa-info-circle text-warning me-1"></i> Lưu ý:</span>
                        <span class="small text-muted">Lý do này sẽ được ghi vào hệ thống để các nhà xe/hãng tàu kiểm tra lại báo giá.</span>
                    </div>

                    <div>
                        <label for="rejectReason" class="form-label text-dark small fw-bold">Lý do chi tiết <span class="text-danger">*</span></label>
                        <textarea class="form-control bg-light" id="rejectReason" name="reject_reason" rows="3" required placeholder="Ví dụ: Bảng giá tháng 4 đã hết hiệu lực, vui lòng cập nhật báo giá mới..." style="border-radius: 10px; resize: none; border: 1px solid #e2e8f0;"></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light px-4 fw-bold rounded-pill border shadow-sm" data-bs-dismiss="modal">
                        Hủy bỏ
                    </button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold rounded-pill shadow-sm">
                        <i class="fas fa-paper-plane me-1"></i> Xác nhận từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>