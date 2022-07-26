<?php


namespace App\Services\ParserManager\Drivers;

use App\Services\ConnectionService\Contracts\ConnectionServiceContract;
use App\Services\ParserManager\Contracts\ParseDriverContract;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use Illuminate\Support\Collection;

abstract class BaseDriver implements ParseDriverContract
{
    /**
     * Get Html by url
     * @param string $url
     * @return mixed
     */
    public function getHtml(string $url): mixed
    {
        $connectionService = app(ConnectionServiceContract::class);

        return $connectionService->getHtml($url);
    }

    /**
     * Remove non-unique key from deep collection
     *
     * @param Collection $collection
     * @param string $key
     * @return Collection
     */
    protected function removeDuplicates(Collection $collection, string $key): Collection
    {
        $tmp = $keyCollection = array();
        $i = 0;
        foreach ($collection as $val) {
            if (!in_array($val->$key, $keyCollection)) {
                $keyCollection[$i] = $val->$key;
                $tmp[$i] = $val;
            }
            $i++;
        }

        return collect($tmp);
    }

    /**
     * Return product
     *
     * @param string $url
     * @return ParserProductDataDTO
     */
    abstract public function parseProduct(string $url): ParserProductDataDTO;

    /**
     * Validation rulers
     * @return array
     */
    abstract protected function validationRules(): array;
}
