<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
        $sql = "SELECT o.*, 
        GROUP_CONCAT(CONCAT(d.quantity, 'x', p.container_type) SEPARATOR ', ') as container_details,
        SUM(d.quantity * p.price) as total_order_price
        FROM logis_orders o
        LEFT JOIN logis_order_details d ON o.id = d.order_id
        LEFT JOIN logis_pricing p ON d.pricing_id = p.id" 
        . $where . 
        " GROUP BY o.id ORDER BY o.id DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. ĐẾM SỐ LƯỢNG CHO CÁC NÚT TAB (Giữ nguyên)
        $count_stmt = $this->pdo->query("SELECT status, COUNT(*) as count FROM logis_orders GROUP BY status");
        $counts = [];
        while ($row = $count_stmt->fetch(PDO::FETCH_ASSOC)) { $counts[$row['status']] = $row['count']; }

        // 6. TÍNH TOÁN BẢNG SUM (Phiên bản Động - Tự động nhận diện mọi loại Cont)
        
        // 6.1. Lấy tất cả các loại Cont đang có trong bảng Giá để làm tiêu đề cột
        $stmtTypes = $this->pdo->query("SELECT container_type FROM logis_pricing ORDER BY id ASC");
        $containerTypes = $stmtTypes->fetchAll(PDO::FETCH_COLUMN); // Mảng chứa ['20 DC', '40 DC', '40 HC'...]

        // 6.2. Khởi tạo mảng thống kê chứa tất cả các loại Cont đó với giá trị ban đầu là 0
        $summary = [
            'ha_rong' => array_fill_keys($containerTypes, 0),
            'cap_rong' => array_fill_keys($containerTypes, 0)
        ];
        $summary['ha_rong']['tong'] = 0;
        $summary['cap_rong']['tong'] = 0;

        // 6.3. Vòng lặp đếm số lượng từ chuỗi (vd: "2x20 DC, 1x40 RF")
        foreach ($orders as $o) {
            $type = ($o['action_type'] == 'Hạ rỗng') ? 'ha_rong' : 'cap_rong';
            
            if (!empty($o['container_details'])) {
                $items = explode(',', $o['container_details']);
                foreach ($items as $item) {
                    $item = trim($item);
                    // Dùng Regex lấy số lượng (trước chữ x) và tên Loại Cont (sau chữ x)
                    if (preg_match('/^(\d+)x(.+)$/', $item, $matches)) {
                        $qty = (int)$matches[1];
                        $cType = trim($matches[2]); 
                        
                        // Nếu loại Cont này có tồn tại trong danh sách thì cộng dồn
                        if (array_key_exists($cType, $summary[$type])) {
                            $summary[$type][$cType] += $qty;
                            $summary[$type]['tong'] += $qty;
                        }
                    }
                }
            }
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
        $id = $_GET['id'] ?? 0;
        
        // 1. Lấy thông tin chung của đơn hàng
        $stmt = $this->pdo->prepare("SELECT * FROM logis_orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            die("Không tìm thấy đơn hàng.");
        }
        
        // 2. Lấy chi tiết các loại Cont của đơn hàng này từ bảng logis_order_details
        $stmtDetails = $this->pdo->prepare("SELECT * FROM logis_order_details WHERE order_id = ?");
        $stmtDetails->execute([$id]);
        $orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. Lấy danh sách 14 loại Cont từ bảng biểu giá để đổ vào thẻ <select>
        $stmtPricing = $this->pdo->query("SELECT * FROM logis_pricing ORDER BY id ASC");
        $pricings = $stmtPricing->fetchAll(PDO::FETCH_ASSOC);
        
        // Gọi ra View Edit
        require_once 'app/Views/orders/edit.php';
    }

    // BẠN DÁN HÀM UPDATE NÀY NGAY BÊN DƯỚI HÀM EDIT NHÉ
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            
            // 1. Cập nhật bảng chính (logis_orders)
            $stmt = $this->pdo->prepare("UPDATE logis_orders SET creator_name=?, action_type=?, depot_name=?, shipping_line=?, bl_do_bkg=?, note=?, status=? WHERE id=?");
            $stmt->execute([
                $_POST['creator_name'], 
                $_POST['action_type'], 
                $_POST['depot_name'], 
                $_POST['shipping_line'], 
                $_POST['bl_do_bkg'], 
                $_POST['note'], 
                $_POST['status'],
                $id
            ]);
            
            // 2. Xóa toàn bộ chi tiết Cont cũ của đơn này trong bảng logis_order_details
            $stmtDel = $this->pdo->prepare("DELETE FROM logis_order_details WHERE order_id = ?");
            $stmtDel->execute([$id]);
            
            // 3. Insert lại danh sách Cont mới từ Form gửi lên (từ Mảng containers)
            if (isset($_POST['containers']) && is_array($_POST['containers'])) {
                $stmtInsert = $this->pdo->prepare("INSERT INTO logis_order_details (order_id, pricing_id, quantity) VALUES (?, ?, ?)");
                foreach ($_POST['containers'] as $container) {
                    // Chỉ lưu nếu có ID bảng giá và số lượng > 0
                    if (!empty($container['pricing_id']) && !empty($container['quantity']) && $container['quantity'] > 0) {
                        $stmtInsert->execute([$id, $container['pricing_id'], $container['quantity']]);
                    }
                }
            }
            
            // Báo thành công và quay về trang danh sách
            $_SESSION['success_msg'] = "Đã cập nhật đơn hàng thành công!";
            header("Location: index.php?page=orders");
            exit;
        }
    }

    public function download() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=orders");
            exit();
        }
        $id = $_GET['id'];

        // 1. Lấy thông tin chung của đơn hàng
        $stmt = $this->pdo->prepare("SELECT * FROM logis_orders WHERE id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            die("Không tìm thấy đơn hàng!");
        }

        // 2. LẤY CHI TIẾT CONTAINER ĐỘNG: Lấy chính xác loại cont và số lượng từ DB
        $stmtDetails = $this->pdo->prepare("
            SELECT p.container_type, d.quantity 
            FROM logis_order_details d
            JOIN logis_pricing p ON d.pricing_id = p.id
            WHERE d.order_id = ?
        ");
        $stmtDetails->execute([$id]);
        $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

        // Khởi tạo một file Excel mới
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Đặt tiêu đề cho Sheet
        $sheet->setTitle('Phiếu Đơn Hàng');

        // Ghi dữ liệu thông tin chung cố định
        $sheet->setCellValue('A1', 'PHIẾU TIẾP NHẬN ĐƠN HÀNG - LOGISPORT');
        $sheet->mergeCells('A1:B1');

        $sheet->setCellValue('A3', 'Mã đơn hàng');
        $sheet->setCellValue('B3', $order['order_code']);

        $sheet->setCellValue('A4', 'Người tạo');
        $sheet->setCellValue('B4', $order['creator_name']);

        $sheet->setCellValue('A5', 'Ngày gửi');
        $sheet->setCellValue('B5', date('d/m/Y H:i', strtotime($order['created_at'])));

        $sheet->setCellValue('A6', 'Phương án');
        $sheet->setCellValue('B6', $order['action_type']);

        $sheet->setCellValue('A7', 'Depot');
        $sheet->setCellValue('B7', $order['depot_name']);

        $sheet->setCellValue('A8', 'Hãng tàu');
        $sheet->setCellValue('B8', $order['shipping_line']);

        $sheet->setCellValue('A9', 'Số BL/DO/BKG');
        $sheet->setCellValue('B9', $order['bl_do_bkg'] ? $order['bl_do_bkg'] : 'N/A');

        $sheet->setCellValue('A11', 'CHI TIẾT CONTAINER');
        $sheet->mergeCells('A11:B11');

        // 3. XỬ LÝ ĐỔ DỮ LIỆU CONTAINER THEO DÒNG ĐỘNG
        // Bắt đầu duyệt từ dòng 12 trở đi, hiển thị đầy đủ tên loại Cont thực tế và đánh số lượng tương ứng
        $currentRow = 12;
        if (empty($details)) {
            $sheet->setCellValue('A' . $currentRow, 'Loại Container');
            $sheet->setCellValue('B' . $currentRow, '0 cont');
            $currentRow++;
        } else {
            foreach ($details as $d) {
                $sheet->setCellValue('A' . $currentRow, $d['container_type']);
                $sheet->setCellValue('B' . $currentRow, $d['quantity'] . ' cont');
                $currentRow++;
            }
        }

        // Ghi chú và Trạng thái tự động dịch chuyển tịnh tiến theo biến $currentRow
        $sheet->setCellValue('A' . $currentRow, 'Ghi chú');
        $sheet->setCellValue('B' . $currentRow, $order['note'] ? $order['note'] : 'Không có');
        $currentRow++;

        $sheet->setCellValue('A' . $currentRow, 'Trạng thái');
        $sheet->setCellValue('B' . $currentRow, mb_strtoupper($order['status'], 'UTF-8'));
        $statusRow = $currentRow; // Lưu lại vị trí dòng trạng thái để định dạng màu sắc

        // 4. THIẾT LẬP STYLE THEO VÙNG DÒNG ĐỘNG (Từ dòng 1 đến $currentRow)
        $sheet->getStyle('A1:B' . $currentRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Định dạng tiêu đề lớn của phiếu
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF3275B2'); 
        $sheet->getStyle('A1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Định dạng dải màu xám cho thanh tiêu đề "CHI TIẾT CONTAINER"
        $sheet->getStyle('A11')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFEAECEF');
        $sheet->getStyle('A11')->getFont()->setBold(true);
        $sheet->getStyle('A11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Định dạng nổi bật cho chữ Trạng thái ở dòng cuối cùng
        $sheet->getStyle('B' . $statusRow)->getFont()->getColor()->setARGB('FFD32F2F');
        $sheet->getStyle('B' . $statusRow)->getFont()->setBold(true);

        // Kẻ viền (Borders) toàn bộ bảng dữ liệu một cách tự động
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A1:B' . $currentRow)->applyFromArray($styleArray);

        // Định dạng font chữ in đậm cột tiêu đề bên trái
        $sheet->getStyle('A3:A' . $currentRow)->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(40);
        
        // Thiết lập chiều cao dòng co giãn theo số lượng hàng được sinh ra
        for ($i = 1; $i <= $currentRow; $i++) {
            if ($i == 1) {
                $sheet->getRowDimension($i)->setRowHeight(35);
            } else {
                $sheet->getRowDimension($i)->setRowHeight(22);
            }
        }

        // XÓA SẠCH BỘ ĐỆM ĐẦU RA: Ngăn chặn triệt để rác text lỗi chui vào cấu trúc nhị phân của file Excel
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Cấu hình header phản hồi và tiến hành xuất file xlsx về trình duyệt
        $filename = "Phieu_Don_Hang_" . $order['order_code'] . ".xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }

    public function rejectOldPrice() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy lý do từ form
            $reason = $_POST['reject_reason'] ?? 'Từ chối do đơn giá cũ';
            
            // Lấy thông tin người duyệt và thời gian
            $approver_name = $_SESSION['username'] ?? 'Admin';
            $approval_date = date('Y-m-d H:i:s');
            $new_status = 'Duyệt từ chối';

            // Cập nhật Database: Chuyển toàn bộ đơn "Chờ duyệt" thành "Duyệt từ chối"
            // Đồng thời nối thêm Lý do vào cột Ghi chú (note)
            $sql = "UPDATE logis_orders 
                    SET status = ?, 
                        approver_name = ?, 
                        approval_date = ?, 
                        note = CONCAT(IFNULL(note, ''), ' [Lý do từ chối: ', ?, ']')
                    WHERE status = 'Chờ duyệt'";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$new_status, $approver_name, $approval_date, $reason]);
        }

        // Cập nhật xong thì đá về trang danh sách và mở sẵn tab "Duyệt từ chối" để xem kết quả
        header("Location: index.php?page=orders&status=tu_choi");
        exit();
    }
}
?>