<?php

namespace App\Listeners;

use App\Events\DisableCorruptedParserDriverEvent;
use App\Models\ParseConfig;
use Illuminate\Support\Facades\Log;
use Throwable;

class DisableCorruptedParserDriverListener
{
    /**
     * @param DisableCorruptedParserDriverEvent $event
     */
    public function handle(DisableCorruptedParserDriverEvent $event)
    {
        try {
            ParseConfig::where('url', $event->parseUrl)->update(['enable' => false,'error' => 'Error parse']);
        } catch (Throwable) {
            Log::info('DisabledCorruptedParserEvent - problem');
        }
    }
}
