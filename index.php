<?php
// 0 có nghĩa là cookie chỉ tồn tại trong phiên làm việc hiện tại
session_set_cookie_params(0, '/TanCang/'); 

// 2. Thiết lập thời gian tự động đăng xuất sau 10 phút nếu không thao tác (tăng tính chuyên nghiệp)
ini_set('session.gc_maxlifetime', 600);

session_start();

// Bật thông báo lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nếu gửi Form đăng nhập (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'app/Controllers/AuthController.php';
    $auth = new AuthController();
    $auth->handleLogin();
    exit();
}

// Kiểm tra xem User đã đăng nhập chưa
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // Chưa đăng nhập -> Ép mở trang Login
    require_once 'app/Controllers/AuthController.php';
    $auth = new AuthController();
    $auth->showLogin();
    exit();
}

// ============================================
// CÁC TRANG BÊN TRONG HỆ THỐNG (Sau khi đã login)
// ============================================
$page = $_GET['page'] ?? 'home'; // Lấy tham số url ?page=...

switch ($page) {
    case 'home':
        require_once 'app/Views/home/index.php';
        break;

    case 'profile':
        require_once 'app/Views/profile/profile.php';
        break;
    
    case 'logout':
        session_destroy();
        header("Location: index.php"); // Quay lại trang login
        break;

    case 'orders':
        require_once 'app/Views/orders/index.php';
        break;

    default:
        echo "404 - Trang không tồn tại";
        break;
}