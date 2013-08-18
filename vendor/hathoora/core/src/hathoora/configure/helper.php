<?php
namespace hathoora\configure;

/**
 * helper class for configuration
 */
class helper
{
    /**
     * Given an array this function adds its elements as property to the object
     * This function runs recrusively adding nodes to parent
     * 
     * @param array $configObject to which we want to add properties 
     * @param array $arrConfig 
     * @param object $parent parent node which is passed by the function to itself in recrusive
     */
    public static function addAsProperty(&$configObject, $arrConfig, &$parent = false)
    {
        if (is_array($arrConfig))
        {
            foreach($arrConfig as $k => $arrV)
            {
                if (is_array($arrV))
                {
                    // when entry already exists
                    if (isset($configObject->$k))
                        $thisObject =& $configObject->$k;
                    else if (is_object($parent))
                        $thisObject =& $parent->$k;
                    else
                    {
                        $configObject->$k = new \stdClass;
                        $thisObject =& $configObject->$k;
                    }
                    self::addAsProperty($configObject, $arrV, $thisObject);
                }
                else
                {
                    if (is_object($parent))
                        $parent->$k = $arrV;
                    else
                        $configObject->$k = $arrV;
                }
            }
        }
    }
}