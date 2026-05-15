<?php
header('Content-Type: application/json');

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

session_start();

use App\Services\NotificationManager;
use App\Services\InAppNotifier;
use App\Controllers\NotificationController;

// Khởi tạo (hoặc lấy lại) đối tượng quản lý thông báo từ Session
if (!isset($_SESSION['notifier'])) {
    $_SESSION['notifier'] = new InAppNotifier();
}
$notifier = $_SESSION['notifier'];

// Khởi tạo Manager và gắn Observer vào
$manager = new NotificationManager();
$manager->attach($notifier);

// Khởi tạo Controller điều phối
$controller = new NotificationController($manager, $notifier);

$action = $_GET['action'] ?? '';

// === ĐỊNH TUYẾN (ROUTING) API ===

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $msg = $controller->triggerEventAction($input['type'], $input['title']);
    echo json_encode(['status' => 'success', 'message' => $msg]);
} 
elseif ($action === 'fetch') {
    echo $controller->fetchNotificationsAPI();
} 
elseif ($action === 'mark_read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $notifier->markAsRead((int)$input['id']);
    echo json_encode(['status' => 'success']);
}
elseif ($action === 'mark_all_read' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifier->markAllAsRead();
    echo json_encode(['status' => 'success']);
}
elseif ($action === 'delete_all' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifier->deleteAll();
    echo json_encode(['status' => 'success']);

    
}