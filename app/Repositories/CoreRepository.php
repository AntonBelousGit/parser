<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

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

    abstract protected function getModelClass();

    /**
     * @return mixed
     */
    protected function startCondition(): mixed
    {
        return clone $this->model;
    }
}
