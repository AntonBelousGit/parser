<?php

declare(strict_types=1);

namespace App\Services\FlavorService;


use App\Models\Flavor;
use App\Repositories\FlavorRepositories;
use App\Services\FlavorService\Contracts\FlavorServiceContract;
use App\Services\FlavorService\Contracts\FlavorValidatorContract;
use Throwable;

class FlavorService implements FlavorServiceContract
{

    protected array $sizes = [];

    /**
     * @param FlavorValidatorContract $flavorValidatorContract
     * @param FlavorRepositories $flavorRepositories
     */
    public function __construct(
        protected FlavorValidatorContract $flavorValidatorContract,
        protected FlavorRepositories $flavorRepositories,
    )
    {
    }

    /**
     * @param array $array
     * @return bool
     */
    public function update(array $array = []): bool
    {
        try {
            foreach ($array as $flavor) {
                $flavor = $this->flavorValidatorContract->validate($flavor);

                $data = [
                    'id' => $flavor['id'],
                    'name' => html_entity_decode($flavor['name']),
                    'code' => $flavor['code']
                ];

                try {
                    $updateFlavor = $this->flavorRepositories->getFlavorByID($data['id']);
                    if ($updateFlavor) {
                        $updateFlavor->update($data);
                    } else {
                        Flavor::create($data);
                    }
                } catch (Throwable $exception) {
                    report('FlavorService error create/update' . $exception);
                    continue;
                }
            }
        } catch (Throwable) {
            report('FlavorService update error');
            return false;
        }
        return true;
    }
}
