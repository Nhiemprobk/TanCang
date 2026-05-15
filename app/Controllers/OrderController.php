<?php

class OrderController {
    
    // Khai báo thuộc tính $pdo cho Controller
    private $pdo;

    // Hàm khởi tạo: Tự động chạy ngay khi gọi OrderController
    public function __construct() {
        global $pdo;
        require_once 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        // 1. LẤY CÁC THAM SỐ TỪ URL
        $current_status = $_GET['status'] ?? 'all';
        $search_text = trim($_GET['search_text'] ?? '');
        $search_type = $_GET['search_type'] ?? 'all';
        
        // Bắt thêm 2 tham số ngày tháng
        $from_date = $_GET['from_date'] ?? '';
        $to_date = $_GET['to_date'] ?? '';
        
        // Tham số phân trang
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($p < 1) $p = 1;
        $offset = ($p - 1) * $limit;

        $status_map = [
            'cho_duyet' => 'Chờ duyệt', 'tu_choi' => 'Duyệt từ chối', 'dong_y' => 'Duyệt đồng ý',
            'da_thanh_toan' => 'Đã thanh toán', 'dang_lam_hang' => 'Đang làm hàng', 
            'hoan_thanh' => 'Hoàn thành', 'cho_dung_don' => 'Chờ dừng đơn'
        ];

        // 2. XÂY DỰNG ĐIỀU KIỆN WHERE
        $where = " WHERE 1=1";
        $params = [];
        
        // Lọc theo Trạng thái
        if ($current_status !== 'all' && isset($status_map[$current_status])) {
            $where .= " AND status = ?";
            $params[] = $status_map[$current_status];
        }
        
        // LỌC THEO NGÀY THÁNG (Sử dụng DATE() để chỉ lấy ngày, bỏ qua giờ phút giây trong DB)
        if (!empty($from_date)) {
            $where .= " AND DATE(created_at) >= ?";
            $params[] = $from_date;
        }
        if (!empty($to_date)) {
            $where .= " AND DATE(created_at) <= ?";
            $params[] = $to_date;
        }

        // Lọc theo Từ khóa
        if (!empty($search_text)) {
            if ($search_type === 'order_code') {
                $where .= " AND order_code LIKE ?"; $params[] = "%$search_text%";
            } elseif ($search_type === 'creator_name') {
                $where .= " AND creator_name LIKE ?"; $params[] = "%$search_text%";
            } else {
                $where .= " AND (order_code LIKE ? OR creator_name LIKE ? OR bl_do_bkg LIKE ?)";
                array_push($params, "%$search_text%", "%$search_text%", "%$search_text%");
            }
        }

        // 3. ĐẾM TỔNG SỐ DÒNG (Để tính tổng số trang)
        $count_sql = "SELECT COUNT(*) FROM logis_orders" . $where;
        $stmt_count = $this->pdo->prepare($count_sql);
        $stmt_count->execute($params);
        $total_records = $stmt_count->fetchColumn();
        $total_pages = ceil($total_records / $limit);

        // 4. LẤY DỮ LIỆU CÓ GIỚI HẠN (LIMIT ... OFFSET ...)
        $sql = "SELECT * FROM logis_orders" . $where . " ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. ĐẾM SỐ LƯỢNG CHO CÁC NÚT TAB (Giữ nguyên)
        $count_stmt = $this->pdo->query("SELECT status, COUNT(*) as count FROM logis_orders GROUP BY status");
        $counts = [];
        while ($row = $count_stmt->fetch(PDO::FETCH_ASSOC)) { $counts[$row['status']] = $row['count']; }

        // 6. TÍNH TOÁN BẢNG SUM (Tính trên dữ liệu đã lọc của trang hiện tại)
        $summary = ['ha_rong' => ['20'=>0,'40'=>0,'45'=>0,'tong'=>0], 'cap_rong' => ['20'=>0,'40'=>0,'45'=>0,'tong'=>0]];
        foreach ($orders as $o) {
            $type = ($o['action_type'] == 'Hạ rỗng') ? 'ha_rong' : 'cap_rong';
            $summary[$type]['20'] += $o['qty_20']; $summary[$type]['40'] += $o['qty_40'];
            $summary[$type]['45'] += $o['qty_45']; $summary[$type]['tong'] += ($o['qty_20'] + $o['qty_40'] + $o['qty_45']);
        }

        require_once 'app/Views/orders/index.php';
    }

    public function delete() {
        // Kiểm tra xem trên URL có truyền id lên không
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
            // Dùng PDO prepare để chống lỗi bảo mật SQL Injection
            $stmt = $this->pdo->prepare("DELETE FROM logis_orders WHERE id = ?");
            $stmt->execute([$id]);
        }
        
        // Xóa xong thì tự động quay ngoắt về lại trang danh sách đơn hàng
        header("Location: index.php?page=orders");
        exit();
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=orders");
            exit();
        }
        $id = $_GET['id'];

        // Nếu người dùng bấm nút "LƯU THAY ĐỔI" (gửi form bằng phương thức POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ Form gửi lên
            $order_code = $_POST['order_code'];
            $creator_name = $_POST['creator_name'];
            $action_type = $_POST['action_type'];
            $depot_name = $_POST['depot_name'];
            $shipping_line = $_POST['shipping_line'];
            $qty_20 = (int)$_POST['qty_20'];
            $qty_40 = (int)$_POST['qty_40'];
            $qty_45 = (int)$_POST['qty_45'];
            $note = $_POST['note'];

            // Chạy lệnh UPDATE
            $stmt = $this->pdo->prepare("UPDATE logis_orders SET order_code=?, creator_name=?, action_type=?, depot_name=?, shipping_line=?, qty_20=?, qty_40=?, qty_45=?, note=? WHERE id=?");
            $stmt->execute([$order_code, $creator_name, $action_type, $depot_name, $shipping_line, $qty_20, $qty_40, $qty_45, $note, $id]);

            // Cập nhật xong thì quay về trang danh sách
            header("Location: index.php?page=orders");
            exit();
        }

        // Nếu là click bình thường (phương thức GET) -> Lấy dữ liệu cũ ra để điền vào Form
        $stmt = $this->pdo->prepare("SELECT * FROM logis_orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            header("Location: index.php?page=orders");
            exit();
        }

        // Gọi giao diện Form sửa và truyền dữ liệu $order sang
        require_once 'app/Views/orders/edit.php';
    }

    public function download() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=orders");
            exit();
        }
        $id = $_GET['id'];

        // Lấy thông tin đơn hàng từ DB
        $stmt = $this->pdo->prepare("SELECT * FROM logis_orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            die("Không tìm thấy đơn hàng!");
        }

        // Tạo nội dung cho file TXT
        $content = "========================================\r\n";
        $content .= "         PHIẾU TIẾP NHẬN ĐƠN HÀNG       \r\n";
        $content .= "           Hệ thống LOGISPORT           \r\n";
        $content .= "========================================\r\n\r\n";
        $content .= "Mã đơn hàng : " . $order['order_code'] . "\r\n";
        $content .= "Người tạo   : " . $order['creator_name'] . "\r\n";
        $content .= "Ngày gửi    : " . date('d/m/Y H:i', strtotime($order['created_at'])) . "\r\n";
        $content .= "Phương án   : " . $order['action_type'] . "\r\n";
        $content .= "Depot       : " . $order['depot_name'] . "\r\n";
        $content .= "Hãng tàu    : " . $order['shipping_line'] . "\r\n";
        $content .= "Số BL/DO/BKG: " . ($order['bl_do_bkg'] ? $order['bl_do_bkg'] : 'N/A') . "\r\n\r\n";
        $content .= "---------------- CHI TIẾT --------------\r\n";
        $content .= "Cont 20'    : " . $order['qty_20'] . " cont\r\n";
        $content .= "Cont 40'    : " . $order['qty_40'] . " cont\r\n";
        $content .= "Cont 45'    : " . $order['qty_45'] . " cont\r\n";
        $content .= "Ghi chú     : " . ($order['note'] ? $order['note'] : 'Không có') . "\r\n";
        $content .= "========================================\r\n";
        $content .= "Trạng thái  : " . strtoupper($order['status']) . "\r\n";

        // Tên file khi tải về máy tính
        $filename = "Phieu_Don_Hang_" . $order['order_code'] . ".txt";

        // Thiết lập Header để ép trình duyệt tải file về thay vì hiển thị ra màn hình
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($content));

        // In nội dung ra (Trình duyệt sẽ gom vào file tải về)
        echo $content;
        exit();
    }
}
?>