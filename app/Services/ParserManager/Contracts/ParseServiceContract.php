<?php
declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

interface ParseServiceContract
{
    /** Call all isset parser and get data
     * @param array $config
     * @return array
     */
    public function callParse(array $config): array;
}
