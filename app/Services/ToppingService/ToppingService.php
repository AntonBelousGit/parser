<?php

declare(strict_types=1);

namespace App\Services\ToppingService;


use App\Models\Topping;
use App\Repositories\ToppingRepositories;
use App\Services\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ToppingService\Contracts\ToppingValidatorContract;
use Throwable;

class ToppingService implements ToppingServiceContract
{

    protected array $sizes = [];

    /**
     * @param ToppingValidatorContract $toppingValidatorContract
     * @param ToppingRepositories $toppingRepositories
     */
    public function __construct(
        protected ToppingValidatorContract $toppingValidatorContract,
        protected ToppingRepositories $toppingRepositories,
    )
    {
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function store(array $array = []): bool
    {
        try {

            foreach ($array as $topping) {

                $topping = $this->toppingValidatorContract->validate($topping);
                $data = [
                    'id' => $topping['id'],
                    'name' => html_entity_decode($topping['name']),
                ];

                try {
                    (new Topping())->create($data);
                } catch (Throwable $e) {

                    report($e);
                    abort(400);
                }
            }
        } catch (Throwable $e) {
            report($e);
            return false;
        }
        return true;
    }

    /**
     * @param array $array
     * @return bool
     */
    public function update(array $array = []): bool
    {
        try {

            foreach ($array as $topping) {

                $topping = $this->toppingValidatorContract->validate($topping);

                $data = [
                    'id' => $topping['id'],
                    'name' => html_entity_decode($topping['name']),
                ];

                try {
                    $update_size = $this->toppingRepositories->getToppingByID($data['id']);
                    if ($update_size) {
                        $update_size->update($data);
                    } else {
                        (new Topping())->create($data);
                    }

                } catch (Throwable $e) {

                    report($e);
                    continue;
                }
            }
        } catch (Throwable $e) {
            report($e);
            return false;
        }
        return true;
    }
}
