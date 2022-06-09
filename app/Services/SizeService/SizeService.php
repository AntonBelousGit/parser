<?php

declare(strict_types=1);

namespace App\Services\SizeService;


use App\Jobs\GetParseSizeAndSaveJob;
use App\Jobs\UpdateSizeAndSaveJob;
use App\Models\Size;
use App\Repositories\SizeRepositories;
use App\Services\SizeService\Contracts\SizeServiceContract;
use App\Services\SizeService\Contracts\SizeValidatorContract;
use Throwable;

class SizeService implements SizeServiceContract
{

    protected array $sizes = [];

    /**
     * @param SizeValidatorContract $sizeDataValidator
     * @param SizeRepositories $sizeRepositories
     */
    public function __construct(
        protected SizeValidatorContract $sizeDataValidator,
        protected SizeRepositories $sizeRepositories,
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

            foreach ($array as $size) {

                $size = $this->sizeDataValidator->validate($size);

                $data = [
                    'id' => $size['id'],
                    'name' => html_entity_decode($size['name']),
                ];

                try {

                    (new Size())->create($data);
//                    GetParseSizeAndSaveJob::dispatch($data);

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

    /**
     * @param array $array
     * @return bool
     */
    public function update(array $array = []): bool
    {
        try {

            foreach ($array as $size) {

                $size = $this->sizeDataValidator->validate($size);

                $data = [
                    'id' => $size['id'],
                    'name' => html_entity_decode($size['name']),
                ];

                try {
                    $update_size = $this->sizeRepositories->getSizeByID($data['id']);
                    if ($update_size) {
                        $update_size->update($data);
//                        UpdateSizeAndSaveJob::dispatch($update_size,$data);
                    } else {
                        (new Size())->create($data);
//                        GetParseSizeAndSaveJob::dispatch($data);
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
