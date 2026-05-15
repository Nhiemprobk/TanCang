<?php
namespace App\Models;

use App\Models\Traits\HasTimestamps;

abstract class Notification {
    use HasTimestamps;

    protected string $title;
    protected string $body;
    protected string $level; // info|warning|urgent
    protected bool $isRead = false;

    public function __construct(string $title, string $body, string $level = 'info') {
        $this->title = $title;
        $this->body = $body;
        $this->level = $level;
        $this->initTimestamps();
    }

    public function isRead(): bool {
        return $this->isRead;
    }

    public function markAsRead(): void {
        $this->isRead = true;
    }

    public function toArray(): array {
        return [
            'title'      => $this->title,
            'body'       => $this->body,
            'level'      => $this->level,
            'is_read'    => $this->isRead,
            'created_at' => $this->getCreatedAt()
        ];
    }
}
