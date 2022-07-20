<?php

namespace App\Events;

use App\Models\ParseConfig;
use Illuminate\Foundation\Events\Dispatchable;

class DisableCorruptedParserEvent
{
    use Dispatchable;

    /**
     * Event for disable corrupted parser in DB
     *
     * DisableCorruptedParserEvent constructor.
     * @param ParseConfig $parseModel
     */
    public function __construct(public ParseConfig $parseModel)
    {
    }
}
