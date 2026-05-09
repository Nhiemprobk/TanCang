<?php require_once 'app/Views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Tổng quan hệ thống</h4>
    <span class="text-muted"><i class="far fa-calendar-alt"></i> Hôm nay: <?= date('d/m/Y') ?></span>
</div>

<!-- WIDGET THỐNG KÊ (Phần thêm mới hoàn toàn so với web gốc) -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="dash-card p-4 border-bottom border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 fw-semibold text-uppercase" style="font-size: 12px;">Đơn Chờ Duyệt</p>
                    <h2 class="fw-bold text-dark mb-0">12</h2>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle">
                    <i class="fas fa-file-invoice fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card p-4 border-bottom border-warning border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 fw-semibold text-uppercase" style="font-size: 12px;">Đang Làm Hàng</p>
                    <h2 class="fw-bold text-dark mb-0">08</h2>
                </div>
                <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle">
                    <i class="fas fa-truck-loading fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card p-4 border-bottom border-success border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 fw-semibold text-uppercase" style="font-size: 12px;">Container Trong Bãi</p>
                    <h2 class="fw-bold text-dark mb-0">450</h2>
                </div>
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle">
                    <i class="fas fa-box fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dash-card p-4 border-bottom border-danger border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 fw-semibold text-uppercase" style="font-size: 12px;">Thông báo mới</p>
                    <h2 class="fw-bold text-dark mb-0">03</h2>
                </div>
                <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                    <i class="fas fa-bell fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KHU VỰC THÔNG BÁO DEPOT -->
<div class="row">
    <div class="col-md-8">
        <div class="dash-card p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="fas fa-bullhorn text-warning me-2"></i> Bảng tin nội bộ</h5>
            
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
                <i class="fas fa-info-circle fs-3 me-3 text-info"></i>
                <div>
                    <strong>Cập nhật Biểu phí nâng rỗng:</strong> Áp dụng biểu phí mới cho các Hãng tàu từ ngày 01/06/2026. Vui lòng kiểm tra mục Danh mục để biết thêm chi tiết.
                </div>
            </div>

            <div class="alert alert-light border shadow-sm d-flex align-items-center">
                <i class="fas fa-tools fs-3 me-3 text-secondary"></i>
                <div>
                    <strong>Bảo trì hệ thống E-EIR:</strong> Hệ thống E-EIR sẽ được bảo trì định kỳ vào 22:00 tối Chủ Nhật tuần này.
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Banner minh họa để thay thế banner đỏ rồng của SINOVNL -->
        <div class="dash-card rounded overflow-hidden" style="height: 100%; min-height: 250px; background: linear-gradient(45deg, #0284c7, #38bdf8); position: relative;">
            <div class="p-4 position-relative" style="z-index: 2; color: white;">
                <h4 class="fw-bold">LOGISPORT</h4>
                <p class="opacity-75">Nền tảng số hóa quy trình Cảng biển thông minh.</p>
                <button class="btn btn-light btn-sm mt-3 fw-bold text-primary rounded-pill px-3">Hỗ trợ ngay <i class="fas fa-arrow-right ms-1"></i></button>
            </div>
            <i class="fas fa-ship position-absolute" style="font-size: 150px; right: -20px; bottom: -20px; color: rgba(255,255,255,0.1); transform: rotate(-15deg);"></i>
        </div>
    </div>
</div>

<?php require_once 'app/Views/layouts/footer.php'; ?>