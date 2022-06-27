<?php

declare(strict_types=1);

namespace App\Services\BaseServices\SizeService;

use App\Models\Size;
use App\Repositories\SizeRepositories;
use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\SizeService\Contracts\SizeValidatorContract;
use Throwable;

class SizeService implements SizeServiceContract
{
    /**
     * @param SizeValidatorContract $sizeDataValidator
     * @param SizeRepositories $sizeRepositories
     */
    public function __construct(
        protected SizeValidatorContract $sizeDataValidator,
        protected SizeRepositories $sizeRepositories,
    ) {
    }

    /**
     * @param array $array
     * @return void
     */
    public function updateOrCreate(array $array = []): void
    {
        try {
            foreach ($array as $size) {
                $size = $this->sizeDataValidator->validate($size);

                $data = [
                    'id' => $size['id'],
                    'name' => html_entity_decode($size['name']),
                ];
                try {
                    $updateSize = $this->sizeRepositories->getSizeByID($data['id']);
                    if ($updateSize) {
                        $updateSize->update($data);
                    } else {
                        Size::create($data);
                    }
                } catch (Throwable) {
                    report('SizeService error create/update');
                }
            }
        } catch (Throwable) {
            report('SizeService update error');
        }
    }
}
