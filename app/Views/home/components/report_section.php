<div class="row g-4 mb-5 mt-4">
    <div class="col-12">
        <div class="dash-card p-4 shadow-sm bg-white rounded-3 border-0">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="d-flex align-items-center justify-content-center" style="width:32px; height:32px;">
                    <i class="fas fa-file-excel text-primary"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Xuất Báo Cáo Lệnh Cảng</h6>
                </div>
            </div>

            <form action="<?= $baseUrl ?>/index.php?page=export_report" method="POST">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-6">
                        <label for="from_date" class="fw-bold text-secondary">Từ ngày</label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden border border-1 border-secondary-subtle">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-calendar-alt text-primary"></i></span>
                            <input type="date" class="form-control border-0" id="from_date" name="from_date" value="<?= date('Y-m-01') ?>" required>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="to_date" class="fw-bold text-secondary">Đến ngày</label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden border border-1 border-secondary-subtle">
                            <span class="input-group-text bg-white border-0"><i class="fas fa-calendar-alt text-primary"></i></span>
                            <input type="date" class="form-control border-0" id="to_date" name="to_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info small mt-4 rounded-4">
                    <i class="fas fa-info-circle me-2"></i> Hệ thống sẽ tự động tổng hợp toàn bộ danh sách lệnh, trạng thái duyệt và tính toán doanh thu trong khoảng thời gian trên vào file Excel.
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100 mt-3 shadow-sm">
                    <i class="fas fa-download me-2"></i> Tải Xuất Báo Cáo Excel (.xlsx)
                </button>
            </form>
        </div>
    </div>
</div>
