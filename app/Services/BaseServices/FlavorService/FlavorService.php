<?php

declare(strict_types=1);

namespace App\Services\BaseServices\FlavorService;

use App\Models\Flavor;
use App\Services\BaseServices\FlavorService\Contracts\FlavorServiceContract;
use App\Services\BaseServices\FlavorService\Contracts\FlavorValidatorContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class FlavorService implements FlavorServiceContract
{
    /**
     * @var array
     */
    protected array $sizes = [];

    /**
     * @param FlavorValidatorContract $flavorValidatorContract
     */
    public function __construct(
        protected FlavorValidatorContract $flavorValidatorContract,
    ) {
    }

    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void
    {
        try {
            foreach ($array as $flavor) {
                $flavor = $this->flavorValidatorContract->validate($flavor);
                $data = [
                    'id' => $flavor['id'],
                    'name' => html_entity_decode($flavor['name']),
                ];
                try {
                    $updateFlavor = Flavor::find($data['id']);
                    if ($updateFlavor) {
                        $updateFlavor->update($data);
                    } else {
                        Flavor::create($data);
                    }
                } catch (Throwable) {
                    Log::info('FlavorService error create/update');
                }
            }
        } catch (Throwable) {
            Log::info('FlavorService update error');
        }
    }
}
