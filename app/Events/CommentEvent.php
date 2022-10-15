<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id_article;
    public mixed $user;
    public mixed $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $id_article,mixed $user,mixed $comment)
    {
        $this->id_article = $id_article;
        $this->user = $user;
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('comment.article.'.$this->id_article);
    }

    public function broadcastAs():string
    {
        return "CommentEvent";
    }

    public function broadcastWith():array
    {
        return [
            "user" => [
                "name" => $this->user->name,
                "role" => $this->user->role,
                "path_photo" => $this->user->path_photo
            ],
            "comment" => $this->comment,
        ];
    }
}
