<?php


class DateFormatConversion
{
    public static function jsonToArray($jsonData)
    {
        if (!self::isJsonValidate($jsonData)) {
            throw new Exception('invalid json format');
        }
        return json_decode($jsonData);
    }

    public static function arrayToJson($arrayData) {
        if(!is_array($arrayData)) {
            throw new Exception('invalid array format');
        }
        return json_encode($arrayData);
    }

    private static function isJsonValidate($string)
    {
        if (is_string($string)) {
            @json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

}
