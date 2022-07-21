<?php

namespace App\Events;

use App\Models\ParseConfig;
use Illuminate\Foundation\Events\Dispatchable;

class DisableCorruptedParserDriverEvent
{
    use Dispatchable;

    /**
     * Event for disable corrupted parser in DB
     *
     * DisableCorruptedParserDriverEvent constructor.
     * @param string $parseUrl
     */
    public function __construct(public string $parseUrl)
    {
    }
}
