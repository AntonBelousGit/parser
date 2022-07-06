<?php

declare(strict_types=1);

namespace App\Services\BaseServices\SizeService;

use App\Models\Size;
use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\SizeService\Contracts\SizeValidatorContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class SizeService implements SizeServiceContract
{
    /**
     * @param SizeValidatorContract $sizeDataValidator
     */
    public function __construct(
        protected SizeValidatorContract $sizeDataValidator,
    ) {
    }

    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void
    {
        foreach ($array as $size) {
            try {
                $size = $this->sizeDataValidator->validate($size);
                $data = [
                    'id' => $size['id'],
                    'name' => html_entity_decode($size['name']),
                ];

                $updateSize = Size::find($data['id']);
                if ($updateSize) {
                    $updateSize->update($data);
                } else {
                    Size::create($data);
                }
            } catch (Throwable) {
                Log::info('SizeService error create/update');
            }
        }
    }
}
