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

<?php include 'components/filter_form.php'; ?>

<div class="dash-card p-3 mb-4">
    <?php include 'components/status_tabs.php'; ?>

    <?php include 'components/main_table.php'; ?>

    <?php include 'components/pagination.php'; ?>
</div>

<?php include 'components/sum_table.php'; ?>

<?php include 'components/reject_modal.php'; ?>

<script>
// Script hỗ trợ đổi màu nút lọc (dù đã có PHP xử lý nhưng giữ lại nếu bạn cần mở rộng sau này)
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

<?php require_once 'app/Views/layouts/footer.php'; ?>