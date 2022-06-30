<?php

declare(strict_types=1);

namespace App\Services\CallParseServices;

use App\Services\CallParseServices\Contracts\CallParseServiceContract;

class CallParseService implements CallParseServiceContract
{
    public function __construct(protected array $parsers)
    {
    }

    /**
     * Call all method parse
     */
    public function callParse(): void
    {
        foreach ($this->parsers as $parser) {
            app()->call($parser['parser'], ['config' => $parser['config']]);
        }
    }
}
