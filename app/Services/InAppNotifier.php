<?php
namespace App\Services;

use App\Services\Interfaces\NotificationObserver;
use App\Models\SystemNotification;

class InAppNotifier implements NotificationObserver {
    private array $notifications = [];

    public function onEvent(string $event, mixed $data): void {
        $name = $data['name'] ?? 'N/A';
        switch ($event) {
            case 'data_created':
                $this->addNotification(new SystemNotification("Tạo mới", "Dữ liệu mới: $name", "info"));
                break;
            case 'data_edited':
                $this->addNotification(new SystemNotification("Cập nhật", "Đã chỉnh sửa: $name", "info"));
                break;
            case 'data_deleted':
                $this->addNotification(new SystemNotification("Xóa dữ liệu", "Đã xóa: $name", "warning"));
                break;
            case 'task_deadline':
                $this->addNotification(new SystemNotification("Sắp đến hạn", "Nhiệm vụ '$name' sắp hết hạn!", "urgent"));
                break;
        }
    }

    private function addNotification($notification): void {
        $this->notifications[] = $notification;
    }

    public function markAsRead(int $index): void {
        if (isset($this->notifications[$index])) {
            $this->notifications[$index]->markAsRead();
        }
    }

    public function countUnread(): int {
        return count(array_filter($this->notifications, fn($n) => !$n->isRead()));
    }

    public function getAllNotifications(): array {
        $result = [];
        foreach ($this->notifications as $id => $noti) {
            $data = $noti->toArray();
            $data['id'] = $id;
            $result[] = $data;
        }
        return $result;
    }

    public function markAllAsRead(): void {
        foreach ($this->notifications as $noti) {
            $noti->markAsRead();
        }
    }

    public function deleteAll(): void {
        $this->notifications = [];
    }
}
