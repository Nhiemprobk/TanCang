<?php
// Thay đổi 'Tên_Database_Của_Bạn' bằng tên DB thực tế trong phpMyAdmin
$host = 'localhost';
$dbname = 'tancang'; 
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>