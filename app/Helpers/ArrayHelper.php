<?php

namespace App\Helpers;

class ArrayHelper {

    /**
     * Из рекурсивно вложенного массива делает обычный массив
     *
     * @param array $array
     * @return array
     */
    public static function normalizeArray(array $array = []): array
    {
        static $out = [];

        foreach ($array as $subArray) {
            if (!empty($subArray['children'])) {
                $arrayToAdd = $subArray;
                unset($arrayToAdd['children']);

                $out[] = $arrayToAdd;
                self::normalizeArray($subArray['children']);
            } else {
                if (isset($subArray['children'])) {
                    unset($subArray['children']);
                }

                $out[] = $subArray;
            }
        }

        return $out;
    }
}
