<?php


namespace App\History;


class ColumnChange
{

    public $column;
    public $from;
    public $to;

    /**
     * ColumnChange constructor.
     * @param $column
     * @param $from
     * @param $to
     */

    public function __construct($column, $from, $to)
    {
        $this->column = $column;
        $this->from = $from;
        $this->to = $to;
    }


}
