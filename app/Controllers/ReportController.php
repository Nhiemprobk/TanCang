<?php
// Đường dẫn: app/Controllers/ReportController.php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportController {
    
    // Hàm hiển thị trang chọn ngày
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }
        require 'app/Views/reports/index.php';
    }

    // Hàm thực hiện xử lý xuất file Excel
    public function exportExcel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=reports");
            exit;
        }

        global $pdo; // Sử dụng kết nối CSDL hiện tại của bạn

        $from_date = $_POST['from_date'] . ' 00:00:00';
        $to_date   = $_POST['to_date'] . ' 23:59:59';

        // 1. Lấy dữ liệu lệnh cảng từ database dựa trên cột created_at
        $sql = "SELECT order_code, creator_name, action_type, depot_name, shipping_line, bl_do_bkg, status, created_at 
                FROM logis_orders 
                WHERE created_at BETWEEN ? AND ?
                ORDER BY created_at DESC";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$from_date, $to_date]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Khởi tạo đối tượng Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Bao Cao Epot');

        // 3. Thiết kế Tiêu đề chính trong file Excel
        $sheet->setCellValue('A1', 'BÁO CÁO QUẢN LÝ LỆNH CẢNG E-POT');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Hiển thị khoảng thời gian lọc
        $sheet->setCellValue('A2', 'Thời gian lệnh: Từ ' . date('d/m/Y', strtotime($_POST['from_date'])) . ' đến ' . date('d/m/Y', strtotime($_POST['to_date'])));
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setItalic(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 4. Tạo Header cho bảng dữ liệu (Dòng số 4)
        $headers = [
            'A4' => 'STT',
            'B4' => 'Mã Đơn Hàng',
            'C4' => 'Người Tạo Lệnh',
            'D4' => 'Loại Tác Vụ',
            'E4' => 'Tên Depot',
            'F4' => 'Hãng Tàu',
            'G4' => 'Số B/L - D/O - BKG',
            'H4' => 'Trạng Thái',
            'I4' => 'Ngày Tạo'
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Định dạng Header (Màu nền xanh navy nhạt, chữ trắng, in đậm, căn giữa)
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78']
            ]
        ];
        $sheet->getStyle('A4:I4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // 5. Đổ dữ liệu vòng lặp vào các dòng tiếp theo
        $rowNumber = 5;
        $stt = 1;
        
        // Mảng mapping trạng thái tiếng Việt
        $statusMap = [
            'cho_duyet' => 'Chờ duyệt',
            'da_duyet'  => 'Đã duyệt',
            'tu_choi'   => 'Từ chối'
        ];

        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $rowNumber, $stt++);
            $sheet->setCellValue('B' . $rowNumber, $order['order_code']);
            $sheet->setCellValue('C' . $rowNumber, $order['creator_name']);
            $sheet->setCellValue('D' . $rowNumber, $order['action_type']);
            $sheet->setCellValue('E' . $rowNumber, $order['depot_name']);
            $sheet->setCellValue('F' . $rowNumber, $order['shipping_line']);
            $sheet->setCellValue('G' . $rowNumber, $order['bl_do_bkg']);
            
            // Format cột trạng thái
            $statusText = $statusMap[$order['status']] ?? $order['status'];
            $sheet->setCellValue('H' . $rowNumber, $statusText);
            
            $sheet->setCellValue('I' . $rowNumber, date('d/m/Y H:i', strtotime($order['created_at'])));

            // Căn giữa một số cột thông tin ngắn
            $sheet->getStyle('A'.$rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B'.$rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H'.$rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I'.$rowNumber)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNumber++;
        }

        // 6. Tạo đường viền (Borders) cho toàn bộ bảng dữ liệu dữ liệu vừa tạo
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9D9D9'],
                ],
            ],
        ];
        $sheet->getStyle('A4:I' . ($rowNumber - 1))->applyFromArray($borderStyle);

        // 7. Tự động co giãn (Auto-size) độ rộng các cột cho vừa khít nội dung chữ
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // 8. Tiến hành cấu hình Header HTTP và xuất file tải trực tiếp về máy
        $fileName = 'Bao_cao_Epot_' . date('Ymd_His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}