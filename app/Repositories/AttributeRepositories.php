<?php


namespace App\Repositories;

use App\Models\Attribute as Model;

/**
 * Class ProductRepositories
 * @package App\Repositories
 */
class AttributeRepositories extends CoreRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAttributeFromArray(array $data)
    {
        return $this->startCondition()->where($data)->first();
    }

}
