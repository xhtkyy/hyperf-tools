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
    function di(string $id) {
        return ApplicationContext::getContainer()->get($id);
    }
}

if (!function_exists('struct_to_array')) {
    function struct_to_array(\Google\Protobuf\Struct $struct): array {
        return json_decode($struct->serializeToJsonString(), true);
    }
}

if (!function_exists('array_to_struct')) {
    function array_to_struct(array $array): \Google\Protobuf\Struct {
        $struct = new \Google\Protobuf\Struct();
        try {
            $struct->mergeFromJsonString(json_encode($array));
        } catch (Exception $e) {
        }
        return $struct;
    }
}
