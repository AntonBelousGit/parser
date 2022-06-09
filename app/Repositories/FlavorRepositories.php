<?php


namespace App\Repositories;

use App\Models\Flavor as Model;

/**
 * Class ProductRepositories
 * @package App\Repositories
 */
class FlavorRepositories extends CoreRepository
{
    /**
     * @inheritDoc
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getFlavorByID($id)
    {
        return $this->startCondition()->find($id);
    }

}
