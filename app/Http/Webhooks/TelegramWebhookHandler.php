<?php

namespace App\Http\Webhooks;

use App\Enums\ChannelEnum;
use App\Enums\StatusEnum;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketMessage;
use DefStudio\Telegraph\Handlers\EmptyWebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Stringable;

class TelegramWebhookHandler extends EmptyWebhookHandler
{
    public function start()
    {
        $this->chat->message("Hello! Select a category:")->keyboard($this->makeCategoryKeyboard())->send();
    }

    public function selectCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->first();


        $incompleteTicket = Ticket::firstOrCreate(
            [
                'chat_id' => $this->chat->chat_id,
                'status' => StatusEnum::Incomplete,
            ],
            [
                'category_slug' => $category->slug,
                'channel' => ChannelEnum::TGLM,
                'subject' => '',
            ]
        );

        $this->chat->message("Enter a subject:")->send();
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $stringText = trim((string) $text);

        $incompleteTicket = Ticket::where('chat_id', $this->chat->chat_id)->where('status', StatusEnum::Incomplete)->first();

        if (!$incompleteTicket) {
            if (substr(strtolower($stringText), 0, 5) == 'hello') {
                $this->start();
                return;
            }
            $this->chat->message("Please type \"Hello\" or press /start command.")->send();
            return;
        }

        if (!$incompleteTicket->subject) {
            if (!$stringText) {
                $this->chat->message("Enter a subject:")->send();
                return;
            }
            $incompleteTicket->update([
                'subject' => $stringText,
            ]);
            $this->chat->message("Enter a message:")->send();
            return;
        }

        if (!$stringText) {
            $this->chat->message("Enter a message:")->send();
            return;
        }
        $incompleteTicket->ticketMessages()->create([
            'text' => $stringText,
        ]);

        $incompleteTicket->update([
            'status' => StatusEnum::New,
        ]);

        event(new TicketCreatedEvent($incompleteTicket));

        $this->chat->message("Ticket " . $incompleteTicket->front_name . " created.")->send();
    }

    private function makeCategoryKeyboard(): Keyboard
    {
        $categories = Category::all();

        $keyboard = Keyboard::make();

        for ($i = 0; $i < $categories->count(); $i = $i + 2) {
            $row = [];
            for ($j = 0; $j < 2; $j++) {
                if ($categories[$i + $j]) {
                    $row[] = Button::make($categories[$i + $j]->name)->action('selectCategory')->param('slug', $categories[$i + $j]->slug);
                }
            }
            $keyboard->row($row);
        }

        return $keyboard;
    }
}
