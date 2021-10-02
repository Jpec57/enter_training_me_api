<?php 

namespace App\Traits;

trait ImportCommandTrait {

    private function handleListCell($cellContent, $delimiter = ","): array
    {
        return array_map(function ($element) {
            return trim($element);
        }, explode($delimiter, $cellContent));
    }
}