<?php


namespace App\Repositories;

use App\Models\Product as Model;

/**
 * Class ProductRepositories
 * @package App\Repositories
 */
class ProductRepositories extends CoreRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getProductByID($id)
    {
        return $this->startCondition()->find($id);
    }

}
