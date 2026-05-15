<?php

class UserController {
    private $pdo;

    public function __construct() {
        global $pdo;
        require_once 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        // ==========================================================
        // 1. XỬ LÝ KHI NGƯỜI DÙNG BẤM NÚT "XÁC NHẬN TẠO" (METHOD POST)
        // ==========================================================
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
            $username = trim($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role_id = $_POST['role_id'];

            try {
                // Thêm vào DB (Mặc định is_active = 1 tức là đang hoạt động)
                $sql = "INSERT INTO users (username, password, full_name, email, phone, role_id, is_active) 
                        VALUES (?, ?, ?, ?, ?, ?, 1)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$username, $password, $full_name, $email, $phone, $role_id]);
                
                // Tránh lỗi gửi lại form khi F5 trang, ta sẽ chuyển hướng (redirect)
                header("Location: index.php?page=users&msg=success");
                exit;
            } catch(PDOException $e) {
                // Nếu trùng username hoặc lỗi DB
                $error_msg = "Tên đăng nhập đã tồn tại hoặc có lỗi xảy ra!";
            }
        }

        // ==========================================================
        // 2. LẤY DỮ LIỆU HIỂN THỊ RA BẢNG NHƯ BÌNH THƯỜNG
        // ==========================================================
        $stmt = $this->pdo->query("
            SELECT u.*, r.role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            ORDER BY u.id DESC
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = [
            'total' => count($users),
            'active' => count(array_filter($users, function($u) {
                return isset($u['is_active']) && $u['is_active'] == 1;
            })),
            'locked' => count(array_filter($users, function($u) {
                return isset($u['is_active']) && $u['is_active'] == 0;
            }))
        ];

        require_once 'app/Views/users/index.php';
    }
}
?>