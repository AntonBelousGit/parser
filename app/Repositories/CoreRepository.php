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
    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */

    abstract protected function getModelClass();

    /**
     * @return Model|mixed|Application
     */
    protected function startCondition()
    {
        return clone $this->model;
    }
}
