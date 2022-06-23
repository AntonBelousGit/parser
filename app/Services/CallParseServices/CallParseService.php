<?php

declare(strict_types=1);

namespace App\Services\CallParseServices;

use App\Services\CallParseServices\Contracts\CallParseServiceContract;
use App\Services\ParseDomino\CallParse\CallParseDomino;
use App\Services\ParseVdhPizza\CallParse\CallParseVdhPizza;
use App\Services\ParseZharPizza\CallParse\CallParseZharPizza;
use Throwable;

class CallParseService implements CallParseServiceContract
{
    /**
     * Call all method parse
     */
    public function callParse(): void
    {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if ($method != 'callParse') {
                $this->{$method}();
            }
        }
    }

    /**
     * Call CallParseDomino for parsing
     */
    protected function dominoParse(): void
    {
        try {
            app()->call(CallParseDomino::class . '@__invoke');
        } catch (Throwable) {
            report('Error Domino');
        }
    }

    /**
     * Call CallParseZharPizza for parsing
     */
    protected function zharPizza(): void
    {
        try {
            app()->call(CallParseZharPizza::class . '@__invoke');
        } catch (Throwable) {
            report('Error ZharPizza');
        }
    }

    /**
     * Call CallParseVdhPizza for parsing
     */
    protected function vdhBar(): void
    {
        try {
            app()->call(CallParseVdhPizza::class . '@__invoke');
        } catch (Throwable) {
            report('Error VdhBar');
        }
    }
}
