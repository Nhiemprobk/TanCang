<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 0 có nghĩa là cookie chỉ tồn tại trong phiên làm việc hiện tại
session_set_cookie_params(0, '/'); 

// Thiết lập thời gian tự động đăng xuất sau 10 phút nếu không thao tác (tăng tính chuyên nghiệp)
ini_set('session.gc_maxlifetime', 600);

session_start();
// Nhúng file autoload của Composer
require_once 'vendor/autoload.php'; 

// Bật thông báo lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

$publicPages = ['login', 'auth', 'register', 'submit_register'];

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

// Lấy tham số url ?page=...
$pageRequest = $_GET['page'] ?? '';

// Nếu gửi Form đăng nhập (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['captcha']) && $pageRequest === 'login') {
    require_once 'app/Controllers/AuthController.php';
    $auth = new AuthController();
    $auth->handleLogin();
    exit();
}

// Kiểm tra xem User đã đăng nhập chưa
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    if (in_array($pageRequest, $publicPages, true)) {
        // Cho phép các trang công khai khi chưa đăng nhập
        $page = $pageRequest;
    } else {
        require_once 'app/Controllers/AuthController.php';
        $auth = new AuthController();
        $auth->showLogin();
        exit();
    }
} else {
    // Nếu đã đăng nhập, chuyển hướng khỏi trang login/register
    if (in_array($pageRequest, ['login', 'register', 'submit_register'], true)) {
        header('Location: index.php?page=home');
        exit();
    }
    $page = $pageRequest ?: 'home';
}

// ============================================
// CÁC TRANG BÊN TRONG HỆ THỐNG (Sau khi đã login)
// ============================================

switch ($page) {
    case 'login':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->showLogin();
        break;

    case 'register':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->showRegister();
        break;

    case 'submit_register':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->handleRegister();
        break;

    case 'home':
        require_once 'app/Views/home/index.php';
        break;

    case 'profile':
        require_once 'app/Controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->index();
        break;
    
    case 'logout':
        require_once 'app/Controllers/AuthController.php';
        $authController = new AuthController();
        $authController->logout();
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
        $userAction = $_GET['action'] ?? '';
        if ($userAction === 'toggle_status') {
            $userCtrl->toggleStatus();
        } elseif ($userAction === 'delete') {
            $userCtrl->delete();
        } else {
            $userCtrl->index();
        }
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
    case 'delete_role':
        require_once 'app/Controllers/RoleController.php';
        $roleController = new RoleController();
        $roleController->delete();
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
        break;

    case 'store_user':
        require_once 'app/Controllers/UserController.php';
        $userController = new UserController();
        $userController->store();
        break;
        
    case 'update_user':
        require_once 'app/Controllers/UserController.php';
        $userController = new UserController();
        $userController->update();
        break;

    default:
        echo "404 - Trang không tồn tại";
        break;
}