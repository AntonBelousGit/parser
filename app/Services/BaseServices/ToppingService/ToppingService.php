<?php

declare(strict_types=1);

namespace App\Services\BaseServices\ToppingService;

use App\Models\Topping;
use App\Repositories\ToppingRepositories;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingValidatorContract;
use Throwable;

class ToppingService implements ToppingServiceContract
{
    /**
     * @param ToppingValidatorContract $toppingValidatorContract
     * @param ToppingRepositories $toppingRepositories
     */
    public function __construct(
        protected ToppingValidatorContract $toppingValidatorContract,
        protected ToppingRepositories $toppingRepositories,
    ) {
    }

    /**
     * @param array $array
     * @return bool
     */
    public function updateOrCreate(array $array = []): bool
    {
        try {
            foreach ($array as $topping) {
                $topping = $this->toppingValidatorContract->validate($topping);

                $data = [
                    'id' => $topping['id'],
                    'name' => html_entity_decode($topping['name']),
                ];

                try {
                    $updateTopping = $this->toppingRepositories->getToppingByID($data['id']);
                    if ($updateTopping) {
                        $updateTopping->update($data);
                    } else {
                        Topping::create($data);
                    }
                } catch (Throwable $exception) {
                    report('ToppingService error create/update'. $exception);
                    continue;
                }
            }
        } catch (Throwable) {
            report('ToppingService update error');
            return false;
        }
        return true;
    }
}
