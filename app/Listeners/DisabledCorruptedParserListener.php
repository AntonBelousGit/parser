<?php

namespace App\Listeners;

use App\Events\DisableCorruptedParserEvent;
use Illuminate\Support\Facades\Log;
use Throwable;

class DisabledCorruptedParserListener
{
    /**
     * @param DisableCorruptedParserEvent $event
     */
    public function handle(DisableCorruptedParserEvent $event)
    {
        try {
            $event->parseModel->update(['enable'=> false]);
        } catch (Throwable) {
            Log::info('DisabledCorruptedParserEvent - problem');
        }
    }
}
