<?php

namespace App\Jobs;

use App\Enums\StatusEnum;
use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class DeleteIncompleteTicketsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->queue = 'tickets';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Ticket::where('created_at', '<', now()->subHours(24))->where('status', StatusEnum::Incomplete)->chunk(200, function (Collection $tickets) {
            foreach ($tickets as $ticket) {
                $ticket->ticketMessages()->delete();
                $ticket->delete();
            }
        });
    }
}
