<?php


namespace App\Repositories;

use App\Models\Topping as Model;

/**
 * Class ProductRepositories
 * @package App\Repositories
 */
class ToppingRepositories extends CoreRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getToppingByID($id)
    {
        return $this->startCondition()->find($id);
    }

}
