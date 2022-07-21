<?php
declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

use App\Services\ParserManager\DTOs\ParserProductDataDTO;

interface ParseManagerContract
{
    /** Call all isset parser and get data
     *
     * @param array $config
     * @return ParserProductDataDTO
     */
    public function callParse(array $config): ParserProductDataDTO;
}
