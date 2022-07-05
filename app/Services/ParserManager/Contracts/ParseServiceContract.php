<?php
declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

interface ParseServiceContract
{
    /** Call all isset parser and get data
     * @return array
     */
    public function callParse(): array;

    /**
     *  Store in DB new parsed data or update
     *  @return void
     */
    public function parse(): void;
}
