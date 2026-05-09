<?php
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
        require_once 'app/Views/home/index.php'; // Giao diện Dashboard bạn vừa tạo ở tin nhắn trước
        break;
    
    case 'logout':
        session_destroy();
        header("Location: index.php"); // Quay lại trang login
        break;

    default:
        echo "404 - Trang không tồn tại";
        break;
}