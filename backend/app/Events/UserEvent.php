<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id;
    public string $created_at;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private readonly User $user,
        public readonly string $title,
        public readonly string $content = '',
        public readonly string $type = 'default',
        public array $meta = [],
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $notification = Notification::create([
            'type' => $this->type,
            'is_read' => false,
            'user_id' => $this->user->id,
            'title' => $this->title,
            'content' => $this->content,
            'meta' => $this->meta,
        ]);
        $this->id = $notification->id;
        $this->created_at = $notification->created_at;
        return [
            new PrivateChannel('users.' . $this->user->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user';
    }
}
