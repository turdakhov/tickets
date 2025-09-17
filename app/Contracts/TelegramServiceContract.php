<?php

namespace App\Contracts;

use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphChat;

interface TelegramServiceContract
{
    public function makeCategoryKeyboard(): Keyboard;

    public function askForSubject(TelegraphChat $chat): void;

    public function askForMessage(TelegraphChat $chat): void;
}
