<?php
declare(strict_types=1);

namespace  App\Traits\Historical\DTOs;

class ColumnChange
{
    /**
     * ColumnChange constructor.
     * @param $column
     * @param $from
     * @param $to
     */
    public function __construct(public $column, public $from, public $to)
    {
    }
}
