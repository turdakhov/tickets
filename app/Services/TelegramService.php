<?php

namespace App\Services;

use App\Contracts\TelegramServiceContract;
use App\Models\Category;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphChat;

class TelegramService implements TelegramServiceContract
{
    public function makeCategoryKeyboard(): Keyboard
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

    public function askForSubject(TelegraphChat $chat): void
    {
        $chat->message("Enter a Subject:")->send();
    }

    public function askForMessage(TelegraphChat $chat): void
    {
        $chat->message("Enter a Message:")->send();
    }
}
