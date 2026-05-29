<?php 
require_once 'app/Views/layouts/header.php'; 
require_once 'config/database.php'; 

// --- 1. LẤY SỐ LIỆU ĐƠN HÀNG CHỜ DUYỆT ---
$stmtPending = $pdo->query("SELECT COUNT(*) FROM logis_orders WHERE status = 'Chờ duyệt'");
$pendingCount = $stmtPending->fetchColumn();

// --- 2. TÍNH TỔNG DOANH THU (Chỉ tính lệnh Đã thanh toán / Hoàn thành) ---
$sqlRevenue = "SELECT SUM(d.quantity * p.price) as total_revenue
               FROM logis_order_details d
               JOIN logis_orders o ON d.order_id = o.id
               JOIN logis_pricing p ON d.pricing_id = p.id
               WHERE o.status IN ('Đã thanh toán', 'Hoàn thành')";
$totalRevenue = $pdo->query($sqlRevenue)->fetchColumn() ?: 0;

// --- 3. LẤY THÔNG BÁO HOẠT ĐỘNG (5 đơn hàng mới nhất) ---
$stmtRecent = $pdo->query("SELECT order_code, creator_name, created_at, status FROM logis_orders ORDER BY created_at DESC LIMIT 5");
$recentOrders = $stmtRecent->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'components/page_header.php'; ?>
<?php include 'components/metrics_cards.php'; ?>
<div class="row g-4">
    <?php include 'components/chart_panel.php'; ?>
    <?php include 'components/recent_activity.php'; ?>
</div>

<?php include 'components/report_section.php'; ?>

<?php include 'components/home_scripts.php'; ?>

<?php require_once 'app/Views/layouts/footer.php'; ?>