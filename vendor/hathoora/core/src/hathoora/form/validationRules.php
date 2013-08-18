<?php
namespace hathoora\form;

class validationRules
{
    /**
     * Returns false when minlength of $input is not $v
     */
    public static function minlength($input, $v)
    {
        return mb_strlen($input, 'UTF-8') >= $v;
    }

    /**
     * Returns false when maxlength of $input is not $v
     */
    public static function maxlength($input, $v)
    {
		return mb_strlen($input, 'UTF-8') <= $v;
    }

    /**
     * Returns true if input if valid url
     * @url http://phpcentral.com/208-url-validation-in-php.html
     */
    public static function url($input)
    {
        //return filter_var($input, FILTER_VALIDATE_URL);

        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(\.\w{2,3}){1,2}(:[0-9]+)?(/.*)?$|i', $url);
    }
    
    /**
     *
     */
    public static function min($input, $v)
    {
    	if(is_numeric(trim($input)))
    	{
    		if($input >= $v) return true;
    		else return false;
    	}
    	else return false;
    }
    
	public static function max($input, $v)
    {
    	if(is_numeric(trim($input)))
    	{
    		if($input <= $v) return true;
    		else return false;
    	}
    	else return false;
    }

    /**
     * Returns true if $input is numeric
     */
    public static function numeric($input)
    {
        return is_numeric(trim($input));
    }

    /**
     * Returns true if $input is alpha
     */
    public static function alpha($input)
    {
        return (preg_match("/[A-Z\s_]/i", trim($input)) > 0) ? true : false;
    }
    
    /**
     * Returns true if $input matches given $regex
     */
    public static function regex($input,$regex)
    {
        return (preg_match('/'.$regex.'/i', $input) > 0) ? true : false;
    }
    
    /**
     * No html
     *
     * @param string $input
     * @param string $option that we want to allow ex: 
     *      passing a would mean we allow <a> tags
     *      passing p,a would mean we allow <a> & <p> tags
     * @maybe there is a better way of doing it using regex?
     */
    public static function noHtml($input, $option = null)
    {
        $input = trim($input);
        if ($option)
        {
            $newOptions = null;
            $arrOptions = explode(',', $option);
            foreach ($arrOptions as $k)
                $newOptions .= '<'. trim($k) .'>';

            return (mb_strlen($input, 'UTF-8') == mb_strlen(strip_tags($input, $newOptions), 'UTF-8'));
        }
        
        return (preg_match('/([\<])([^\>]{1,})*([\>])/i',$input) > 0) ? false : true;
    }
    
    /**
     * Only  html
     *
     * @param string $input
     * @param string $option (comma seperated) that we don't want to allow
     *      passing a would mean we don't allow <a> tags
     * @maybe there is a better way of doing it using regex?
     */
    public static function notTheseHtmlTags($input, $option)
    {
        $arrOptions = explode(',', $option);
        foreach ($arrOptions as $k)
            $newOptions[] = trim($k);
        return (mb_strlen($input, 'UTF-8') == mb_strlen(strip_only($input, $newOptions, true), 'UTF-8'));
    }    

}