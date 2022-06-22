<?php
declare(strict_types=1);


namespace App\Services\CallParseServices\Contracts;


interface CallParseServiceContract
{
    /** Call all isset parser
     * @return void
     */
    public function callParse(): void;
}
