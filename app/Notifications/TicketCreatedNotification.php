<?php

namespace App\Notifications;

use App\Enums\ChannelEnum;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class TicketCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private ?Ticket $ticket)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }


    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->headerBlock('New Support Ticket (' . $this->ticket->category->name . ')')
            ->contextBlock(function (ContextBlock $block) {
                $block->text($this->ticket->channel === ChannelEnum::WEB ? $this->ticket->user->email . ' / ID' . $this->ticket->user->id : 'Telegram User');
                $block->text('Channel: ' . $this->ticket->channel->name);
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->field("*Subject:*\n" . $this->ticket->subject)->markdown();
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->field("*Message:*\n" . $this->ticket->ticketMessages()->first()->text)->markdown();
            });
    }
}
