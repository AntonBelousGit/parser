<?php


namespace App\Repositories;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class CoreRepository
 * @package App\Repositories
 *
 */

abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected mixed $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */

    abstract protected function getModelClass(): mixed;

    /**
     * @return mixed
     */
    protected function startCondition(): mixed
    {
        return clone $this->model;
    }
}
