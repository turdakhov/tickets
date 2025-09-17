<?php

namespace App\Http\Webhooks;

use App\Contracts\TelegramServiceContract;
use App\Enums\ChannelEnum;
use App\Enums\StatusEnum;
use App\Events\TicketCreatedEvent;
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

    private ?string $stringText;

    private ?Ticket $incompleteTicket;

    public function __construct(private TelegramServiceContract $telegramService) {}

    public function start()
    {
        $this->chat->message("Hello! Select a category:")->keyboard($this->telegramService->makeCategoryKeyboard())->send();
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

        $this->telegramService->askForSubject($this->chat);
    }

    protected function handleChatMessage(Stringable $text): void
    {
        $this->stringText = trim((string) $text);

        $this->incompleteTicket = Ticket::where('chat_id', $this->chat->chat_id)->where('status', StatusEnum::Incomplete)->first();

        if (!$this->incompleteTicket) {
            $this->handleMessageWithoutIncompleteTicket();
            return;
        }

        if (!$this->incompleteTicket->subject) {
            $this->handleMessageWhenSubjectIsEmpty();
            return;
        }

        if (!$this->stringText) {
            $this->telegramService->askForMessage($this->chat);
            return;
        }

        $this->handleMessageWithFullData();
    }

    private function handleMessageWithoutIncompleteTicket(): void
    {
        if (substr(strtolower($this->stringText), 0, 5) == 'hello') {
            $this->start();
            return;
        }
        $this->chat->message("Please type \"Hello\" or press /start command.")->send();
    }

    private function handleMessageWhenSubjectIsEmpty(): void
    {
        if (!$this->stringText) {
            $this->telegramService->askForSubject($this->chat);
        }
        $this->incompleteTicket->update([
            'subject' => $this->stringText,
        ]);
        $this->telegramService->askForMessage($this->chat);
    }

    private function handleMessageWithFullData()
    {
        $this->incompleteTicket->ticketMessages()->create([
            'text' => $this->stringText,
        ]);

        $this->incompleteTicket->update([
            'status' => StatusEnum::New,
        ]);

        event(new TicketCreatedEvent($this->incompleteTicket));

        $this->chat->message("Ticket " . $this->incompleteTicket->front_name . " created.")->send();
    }
}
