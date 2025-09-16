<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = Ticket::all();
        foreach ($tickets as $ticket) {
            TicketMessage::factory(random_int(1,3))->create([
                'ticket_id' => $ticket->id,
            ]);
        }
    }
}
