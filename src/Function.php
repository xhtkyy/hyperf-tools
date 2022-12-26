<?php

use Hyperf\Utils\ApplicationContext;

if (!function_exists('toArray')) {
    /**
     * @param string|array $value
     * @return array
     */
    function toArray(string|array $value): array {
        return array_filter(is_array($value) ? $value : (is_string($value) ? explode(",", $value) : []));
    }
}

if (!function_exists('di')) {
    function di(string $id){
        return ApplicationContext::getContainer()->get($id);
    }
}