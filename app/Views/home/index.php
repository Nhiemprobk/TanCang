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

// --- DOANH THU THEO TUẦN HIỆN TẠI + CỘT HÔM NAY ---
$weekLabels = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
$revenueLabels = $weekLabels;
$revenueLabels[] = 'Hôm nay';

$revenueData = [];

// Lấy ngày Thứ 2 đầu tuần và Chủ nhật cuối tuần theo thời gian hiện tại
$monday = date('Y-m-d', strtotime('monday this week'));
$sunday = date('Y-m-d', strtotime('sunday this week'));
$today = date('Y-m-d');

// Lấy doanh thu từng ngày trong tuần hiện tại
$stmtWeekRevenue = $pdo->prepare("
    SELECT 
        DATE(o.created_at) AS revenue_date,
        COALESCE(SUM(d.quantity * p.price), 0) AS daily_revenue
    FROM logis_orders o
    LEFT JOIN logis_order_details d ON o.id = d.order_id
    LEFT JOIN logis_pricing p ON d.pricing_id = p.id
    WHERE DATE(o.created_at) BETWEEN ? AND ?
    AND o.status IN ('Đã thanh toán', 'Hoàn thành')
    GROUP BY DATE(o.created_at)
");
$stmtWeekRevenue->execute([$monday, $sunday]);

$weeklyRevenueMap = [];
while ($row = $stmtWeekRevenue->fetch(PDO::FETCH_ASSOC)) {
    $weeklyRevenueMap[$row['revenue_date']] = (float)$row['daily_revenue'];
}

// Tạo đủ 7 ngày Thứ 2 đến Chủ nhật, ngày nào không có dữ liệu thì = 0
for ($i = 0; $i < 7; $i++) {
    $date = date('Y-m-d', strtotime($monday . " +$i days"));
    $amount = $weeklyRevenueMap[$date] ?? 0;
    $revenueData[] = round($amount / 1000000, 1);
}

// Tính riêng doanh thu hôm nay để thêm cột thứ 8
$stmtTodayRevenue = $pdo->prepare("
    SELECT COALESCE(SUM(d.quantity * p.price), 0) AS today_revenue
    FROM logis_orders o
    LEFT JOIN logis_order_details d ON o.id = d.order_id
    LEFT JOIN logis_pricing p ON d.pricing_id = p.id
    WHERE DATE(o.created_at) = ?
    AND o.status IN ('Đã thanh toán', 'Hoàn thành')
");
$stmtTodayRevenue->execute([$today]);
$todayRevenue = (float)$stmtTodayRevenue->fetchColumn();

$revenueData[] = round($todayRevenue / 1000000, 1);
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