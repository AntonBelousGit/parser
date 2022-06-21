<?php


namespace App\Repositories;

use App\Models\Product as Model;
use Illuminate\Database\Eloquent\Collection;

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

    public function getProductHistory($id): \Illuminate\Database\Eloquent\Model|Collection|array|null
    {
        return $this->startCondition()->with('attributeProduct.history')->find($id);
    }
}
