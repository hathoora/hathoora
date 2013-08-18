<?php
namespace hathoora\helper;

class arrayHelper
{
    public static function arrayMergeRecursiveOverwrite($arr, $toArr)
    {
        $finalArray = $toArr;
        
        if (is_array($arr))
        {
            foreach($arr as $k => $v)
            {
                if (is_array($v))
                {
                    foreach($v as $k2 => $v2)
                    {
                        $finalArray[$k][$k2] = $v2;
                    }
                }
                else
                    $finalArray[$k] = $v;
            }
        }
        
        return $finalArray;
    }

}