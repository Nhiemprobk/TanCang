<?php
namespace App\Services;

use App\Services\Interfaces\NotificationObserver;

class NotificationManager {
    private array $observers = [];

    public function attach(NotificationObserver $o): void {
        $this->observers[] = $o;
    }

    public function notify(string $event, mixed $data = null): void {
        foreach ($this->observers as $observer) {
            $observer->onEvent($event, $data);
        }
    }
}
