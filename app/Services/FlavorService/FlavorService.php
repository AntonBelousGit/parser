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
     * @return mixed
     */
    public function store(array $array = []): bool
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
                    (new Flavor())->create($data);
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

            foreach ($array as $flavor) {

                $flavor = $this->flavorValidatorContract->validate($flavor);

                $data = [
                    'id' => $flavor['id'],
                    'name' => html_entity_decode($flavor['name']),
                    'code' => $flavor['code']
                ];

                try {
                    $update_size = $this->flavorRepositories->getFlavorByID($data['id']);
                    if ($update_size) {
                        $update_size->update($data);
                    } else {
                        (new Flavor())->create($data);
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
