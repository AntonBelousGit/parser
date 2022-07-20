<?php
declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ParseManagerContract
{
    /** Call all isset parser and get data
     *
     * @param Collection $config
     * @return array
     */
    public function callParse(Collection $config): array;
}
