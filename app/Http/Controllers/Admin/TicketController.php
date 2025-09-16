<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ChannelEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tickets\StoreTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function create()
    {
        $categories = Category::all();

        return Inertia::render('tickets/Create', compact(['categories']));
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $category = Category::find($data['category_id']);

            $ticket = Ticket::create([
                'user_id' => auth()->user()->id,
                'channel' => ChannelEnum::WEB,
                'category_slug' => $category->slug,
                'subject' => $data['subject'],
            ]);

            $ticket->ticketMessages()->create([
                'sender_id' => auth()->user()->id,
                'text' => $data['message'],
            ]);
        });

        return redirect()->route('dashboard')->with('message', 'Ticket successfully submitted!');
    }

}
