<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BookReserved extends Event implements ShouldBroadcast
{
    use SerializesModels;
    public $book_reserved = '';
    public $reserved_by = '';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($book_reserved, $reserved_by)
    {
        //
        $this->book_reserved = $book_reserved;
        $this->reserved_by = $reserved_by;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['book-reserved'];
    }
}
