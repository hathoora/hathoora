<?php
namespace hathoora\controller;

use hathoora\container,
    hathoora\http\response,
    hathoora\template\template;


class controller extends container
{
    public function __construct() { }
    
    /**
     * Function for setting tpl variables that would be available in all templates
     *
     * @param mixed $key
     * @param mixed $value
     */
    final public function setTplVars($key, $value)
    {
        $controller = $this->getController();
        $controller->_tpl_vars[$key] = $value;
    }
    
    /**
     * Function for setting tpl variable value by ref that would be available in all templates
     *
     * @param mixed $key
     * @param mixed $value
     */
    final public function setTplVarsByRef($key, &$value)
    {
        $controller = $this->getController();
        $controller->_tpl_vars[$key] =& $value;
    }    
    
    /**
     * Helper for controller which creates an instance of template object
     * 
     * @param string $tpl
     * @param array $vars to assign to template
     * @return \hathoora\template\template
     */    
    final public function template($tpl, $vars = array())
    {
        $template = new template($tpl, $vars);
        
        return $template;
    }
    
    /**
     * Helper for controller which creates an instance of response object
     * 
     * @param mixed $content string or \hathoora\template\template object
     * @param string $arrHeaders
     * @param string $status http status
     * @return \hathoora\http\response
     */    
    final public function response($content = false, $arrHeaders = array(), $status = 200)
    {
        $response = new response($content, $arrHeaders, $status);
        
        return $response;
    }    
}