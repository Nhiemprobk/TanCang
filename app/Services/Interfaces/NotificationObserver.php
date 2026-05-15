<?php
namespace App\Services\Interfaces;

interface NotificationObserver {
    public function onEvent(string $event, mixed $data): void;
}
