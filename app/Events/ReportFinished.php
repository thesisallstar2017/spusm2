<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReportFinished extends Event implements ShouldBroadcast
{
    use SerializesModels;
    public $filename = '';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($filename)
    {
        //
        $this->filename = $filename;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['report-finished'];
    }
}
