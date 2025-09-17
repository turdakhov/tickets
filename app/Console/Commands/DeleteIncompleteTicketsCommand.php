<?php

namespace App\Console\Commands;

use App\Jobs\DeleteIncompleteTicketsJob;
use Illuminate\Console\Command;

class DeleteIncompleteTicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-incomplete-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes Old Incomplete Tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DeleteIncompleteTicketsJob::dispatch();
    }
}
