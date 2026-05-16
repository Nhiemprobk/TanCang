<?php

class UserController {
    private $pdo;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        // Lấy danh sách user kèm theo tên quyền
        $stmt = $this->pdo->query("SELECT u.*, r.role_name as role_name 
                                   FROM users u 
                                   LEFT JOIN Roles r ON u.role_id = r.id 
                                   ORDER BY u.id DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // LẤY DANH SÁCH QUYỀN TỪ DB ĐỂ ĐỔ VÀO POPUP THÊM/SỬA
        $stmtRoles = $this->pdo->query("SELECT id, role_name FROM Roles ORDER BY level ASC");
        $allRoles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

        $stats = [
            'total' => count($users),
            'active' => count(array_filter($users, function($u) { return isset($u['is_active']) && $u['is_active'] == 1; })),
            'locked' => count(array_filter($users, function($u) { return isset($u['is_active']) && $u['is_active'] == 0; }))
        ];

        require 'app/Views/users/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role_id = $_POST['role_id'];

            try {
                $stmt = $this->pdo->prepare("INSERT INTO users (username, password, full_name, email, phone, role_id, is_active) VALUES (?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$username, $password, $full_name, $email, $phone, $role_id]);
                $_SESSION['success_msg'] = "Thêm tài khoản thành công!";
            } catch (PDOException $e) {
                $_SESSION['error_msg'] = "Tên đăng nhập hoặc email đã tồn tại!";
            }
            header("Location: index.php?page=users");
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $full_name = trim($_POST['full_name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role_id = $_POST['role_id'];

            $stmt = $this->pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, role_id = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone, $role_id, $id]);
            
            $_SESSION['success_msg'] = "Cập nhật thông tin thành công!";
            header("Location: index.php?page=users");
            exit();
        }
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$new_password, $id]);

            $_SESSION['success_msg'] = "Khôi phục mật khẩu thành công!";
            header("Location: index.php?page=users");
            exit();
        }
    }

    public function toggleStatus() {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            $id = $_GET['id'];
            $status = $_GET['status'];

            $stmt = $this->pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
            $stmt->execute([$status, $id]);

            $_SESSION['success_msg'] = "Cập nhật trạng thái thành công!";
            header("Location: index.php?page=users");
            exit();
        }
    }
}