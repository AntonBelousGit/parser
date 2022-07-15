<?php
declare(strict_types=1);

if (!function_exists('arrayUniqueKey')) {

    /**
     * Remove non-unique key from deep array
     * @param $array
     * @param $key
     * @return array
     */
    function arrayUniqueKey($array, $key): array
    {
        $tmp = $keyArray = array();
        $i = 0;

        foreach ($array as $val) {
            if (!in_array($val[$key], $keyArray)) {
                $keyArray[$i] = $val[$key];
                $tmp[$i] = $val;
            }
            $i++;
        }
        return $tmp;
    }
}
if (!function_exists('collectionUniqueKey')) {

    /**
     * Remove non-unique key from deep collection
     *
     * @param $collection
     * @param $key
     * @return array
     */
    function collectionUniqueKey($collection, $key): array
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
        return $tmp;
    }
}
