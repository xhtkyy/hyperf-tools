<?php

use Google\Protobuf\Internal\Message;
use Google\Protobuf\Struct;
use Hyperf\Utils\ApplicationContext;
use function Swoole\Coroutine\map;

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
    function struct_to_array(Struct|Message $struct): array {
        return json_decode($struct->serializeToJsonString(), true);
    }
}

if (!function_exists('array_to_struct')) {
    function array_to_struct(array $array): Struct {
        $struct = new Struct();
        try {
            $struct->mergeFromJsonString(json_encode($array));
        } catch (Exception $e) {
        }
        return $struct;
    }
}

if (!function_exists("repeated_field_to_array")) {
    function repeated_field_to_array(\Google\Protobuf\Internal\RepeatedField $repeatedField): array {
        return map(iterator_to_array($repeatedField), function (Message|Struct $item) {
            return struct_to_array($item);
        });
    }
}
