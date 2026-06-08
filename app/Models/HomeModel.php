<?php

class HomeModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getPendingOrdersCount(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM logis_orders WHERE status = 'Chờ duyệt'");
        return (int)$stmt->fetchColumn();
    }

    public function getTotalRevenue(): float {
        $sql = "SELECT SUM(d.quantity * p.price) as total_revenue
                FROM logis_order_details d
                JOIN logis_orders o ON d.order_id = o.id
                JOIN logis_pricing p ON d.pricing_id = p.id
                WHERE o.status IN ('Đã thanh toán', 'Hoàn thành')";
        $stmt = $this->pdo->query($sql);
        return (float)($stmt->fetchColumn() ?: 0);
    }

    public function getRevenueSummary(): array {
        $weekLabels = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
        $revenueLabels = $weekLabels;
        $revenueLabels[] = 'Hôm nay';

        $monday = date('Y-m-d', strtotime('monday this week'));
        $sunday = date('Y-m-d', strtotime('sunday this week'));
        $today = date('Y-m-d');

        $stmtWeekRevenue = $this->pdo->prepare(
            "SELECT DATE(o.created_at) AS revenue_date,
                    COALESCE(SUM(d.quantity * p.price), 0) AS daily_revenue
             FROM logis_orders o
             LEFT JOIN logis_order_details d ON o.id = d.order_id
             LEFT JOIN logis_pricing p ON d.pricing_id = p.id
             WHERE DATE(o.created_at) BETWEEN ? AND ?
               AND o.status IN ('Đã thanh toán', 'Hoàn thành')
             GROUP BY DATE(o.created_at)"
        );
        $stmtWeekRevenue->execute([$monday, $sunday]);

        $weeklyRevenueMap = [];
        while ($row = $stmtWeekRevenue->fetch(PDO::FETCH_ASSOC)) {
            $weeklyRevenueMap[$row['revenue_date']] = (float)$row['daily_revenue'];
        }

        $revenueData = [];
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime($monday . " +$i days"));
            $amount = $weeklyRevenueMap[$date] ?? 0;
            $revenueData[] = round($amount / 1000000, 1);
        }

        $stmtTodayRevenue = $this->pdo->prepare(
            "SELECT COALESCE(SUM(d.quantity * p.price), 0) AS today_revenue
             FROM logis_orders o
             LEFT JOIN logis_order_details d ON o.id = d.order_id
             LEFT JOIN logis_pricing p ON d.pricing_id = p.id
             WHERE DATE(o.created_at) = ?
               AND o.status IN ('Đã thanh toán', 'Hoàn thành')"
        );
        $stmtTodayRevenue->execute([$today]);
        $todayRevenue = (float)$stmtTodayRevenue->fetchColumn();
        $revenueData[] = round($todayRevenue / 1000000, 1);

        return [
            'weekLabels' => $weekLabels,
            'revenueLabels' => $revenueLabels,
            'revenueData' => $revenueData,
        ];
    }

    public function getRecentOrders(int $limit = 5): array {
        $stmt = $this->pdo->prepare(
            "SELECT order_code, creator_name, created_at, status
             FROM logis_orders
             ORDER BY created_at DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
