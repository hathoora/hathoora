<?php
namespace hathoora\admin\helper;

/**
 * translation filder
 */
class translationFilter
{
    /**
     * Sample custom folder used in one of the examples
     */
    public static function customFilter($value, $len)
    {
        return str_repeat(strtolower($value) . ' ---- ' , 3);
    }
}