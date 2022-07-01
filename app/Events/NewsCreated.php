<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\News;


class NewsCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	
	protected $news;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(News $news,)
    {
	   $this->news = $news;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
		return new PresenceChannel('News.'.$this->news->title);
    }
	
	
	
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'title'       =>    $this->news->title,
            'conent'      =>    $this->news->content,
            'author' 	  =>    $this->news->user->name,
            'createdAt'   =>    $this->news->created_at
        ];
    }
}
