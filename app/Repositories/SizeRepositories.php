<?php


namespace App\Repositories;

use App\Models\Size as Model;

/**
 * Class ProductRepositories
 * @package App\Repositories
 */
class SizeRepositories extends CoreRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getSizeByID($id)
    {
        return $this->startCondition()->find($id);
    }

}
