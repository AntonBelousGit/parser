<?php
declare(strict_types=1);

namespace App\History;

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
