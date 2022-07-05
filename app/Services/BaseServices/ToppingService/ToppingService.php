<?php

declare(strict_types=1);

namespace App\Services\BaseServices\ToppingService;

use App\Models\Topping;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingValidatorContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class ToppingService implements ToppingServiceContract
{
    /**
     * @param ToppingValidatorContract $toppingValidatorContract
     */
    public function __construct(
        protected ToppingValidatorContract $toppingValidatorContract,
    ) {
    }

    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void
    {
        try {
            foreach ($array as $topping) {
                $topping = $this->toppingValidatorContract->validate($topping);
                $data = [
                    'id' => $topping['id'],
                    'name' => html_entity_decode($topping['name']),
                ];
                try {
                    $updateTopping = Topping::find($data['id']);
                    if ($updateTopping) {
                        $updateTopping->update($data);
                    } else {
                        Topping::create($data);
                    }
                } catch (Throwable) {
                    Log::info('ToppingService error create/update');
                }
            }
        } catch (Throwable) {
            Log::info('ToppingService update error');
        }
    }
}
