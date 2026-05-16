<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 0 có nghĩa là cookie chỉ tồn tại trong phiên làm việc hiện tại
session_set_cookie_params(0, '/'); 

// 2. Thiết lập thời gian tự động đăng xuất sau 10 phút nếu không thao tác (tăng tính chuyên nghiệp)
ini_set('session.gc_maxlifetime', 600);

session_start();
// Nhúng file autoload của Composer
require_once 'vendor/autoload.php'; 

// Bật thông báo lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    // Nếu đã lưu thời gian hoạt động cuối, và thời gian đó cách hiện tại quá 10 phút (600 giây)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 600)) {
        // Hủy toàn bộ phiên đăng nhập
        session_unset();     
        session_destroy();   
        
        // Chuyển hướng người dùng về trang đăng nhập với thông báo
        header("Location: index.php?timeout=1"); 
        exit();
    }
    // Nếu vẫn đang thao tác, cập nhật lại mốc thời gian hoạt động thành hiện tại
    $_SESSION['last_activity'] = time(); 
}

// Bật thông báo lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Nếu gửi Form đăng nhập (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
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
        require_once 'app/Controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->index();
        break;
    
    case 'logout':
        session_destroy();
        header("Location: index.php"); // Quay lại trang login
        break;

    case 'orders':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->index();
        break;

    case 'delete_order':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->delete();
        break;

    case 'edit_order':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->edit();
        break;

    case 'reject_old_price':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->rejectOldPrice();
        break;
  
    case 'users':
        require_once 'app/Controllers/UserController.php';
        $userCtrl = new UserController();
        $userCtrl->index();
        break;
    
    case 'pricing':
        require_once 'app/Controllers/PricingController.php';
        $pricingController = new PricingController();
        $pricingController->index();
        break;
    case 'update_pricing':
        require_once 'app/Controllers/PricingController.php';
        $pricingController = new PricingController();
        $pricingController->update();
        break;

    case 'update_order':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->update();
        break;
    
    case 'change_status':
        require_once 'app/Controllers/OrderController.php';
        $orderController = new OrderController();
        $orderController->changeStatus();
        break;

    // --- QUẢN LÝ PHÂN QUYỀN ---
    case 'roles':
        require_once 'app/Controllers/RoleController.php';
        $roleController = new RoleController();
        $roleController->index();
        break;
    case 'store_role':
        require_once 'app/Controllers/RoleController.php';
        $roleController = new RoleController();
        $roleController->store();
        break;
    case 'export':
        require_once 'config/database.php';
        require_once __DIR__ . '/app/Export/BaseExport.php';
        require_once __DIR__ . '/app/Export/OrderExport.php';
        require_once __DIR__ . '/app/Controllers/ExportController.php';
        
        $controller = new \App\Controllers\ExportController($pdo);
        $action = $_GET['action'] ?? '';
        if ($action === 'order') {
            $controller->order();
        }
    default:
        echo "404 - Trang không tồn tại";
        break;
}