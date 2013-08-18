<?php
namespace hathoora\template;

use hathoora\container,
    hathoora\logger\logger;

    
/**
 * Smarty template
 *
 */
class templateSmarty extends \Smarty\Smarty implements templateInterface
{
    /**
     * constructor
     *
     * @param array of $config
     */
    public function __construct($config)
    {
        parent::__construct();
        
        if (isset($config['caching']))
            $this->caching = $config['caching'];
            
        if (isset($config['cache_lifetime']))
            $this->cache_lifetime = $config['cache_lifetime'];

        if (isset($config['template_dir']))
            $this->template_dir = $config['template_dir'];

        if (isset($config['cache_dir']))
            $this->cache_dir = $config['cache_dir'];

        if (isset($config['compile_dir']))
            $this->compile_dir = $config['compile_dir'];
            
        if (isset($config['force_compile']))
            $this->force_compile = $config['force_compile'];

        if (isset($config['compile_check']))
            $this->compile_check = $config['compile_check'];
            
        $this->addPluginsDir(__DIR__ .'/smarty/plugins');
    }

    /**
     * Assign variable to be used in template
     *
     * @param string $name of the variable
     * @param mixed $value of the variable
     */
    public function __assign($name, $value)
    {
        return parent::assign($name, $value);
    }

    /**
    * Assign variable, by reference, to be used in template
    *
    * @param string $name of the variable
    * @param mixed $value of the variable
    */
    public function __assignByRef($name, &$value)
    {
        return parent::assignByRef($name, $value);
    }
    
    /**
     * Assign variables from controller->_tpl_vars to view
     */
    public function __assignControllerTPLVars()
    {
        // any _tpl_vars from controller\base class?
        $controller = container::getController();
        if (is_array($controller->_tpl_vars))
        {
            foreach($controller->_tpl_vars as $k => $v)
            {
                $this->assign($k, $v);
            }
        }
    }    

    /**
     * Append variable to be used in template
     *
     * @param string $name of the variable
     * @param mixed $value of the variable
     */
    public function __append($name, $value)
    {
        return parent::append($name, $value);
    }
    
     /**
     * Determines if an entry is cached
     *
     * @param string $template
     * @param string $id Unique ID of this data
     * @param string $group Group to store data under
     */
    public function __isCached($template, $id, $group = null)
    {
        return parent::isCached($template, $id, $group);
    }

    /**
     * Return variable value
     *
     * @param string $name of the variable
     * @return value of variable
     */
    public function __getVar($name)
    {
    
    }
    
    /**
     * Return all variables
     */
    public function __getVars()
    {
    
    }

    /**
     * Include a template
     */
    public function __load($file, $vars = array())
    {
    
    }

    /**
     * Returns flash message and clears flash session
     */
    public function __getFlashMessage()
    {
    
    }
    
    /**
     * a wrapper - fetches a rendered template
     * 
     * @param string $template the resource handle of the template file or template object
     * @param mixed $cache_id cache id to be used with this template
     * @param array $arrExtra for additional requirements
     * @return string rendered template output
     */
    public function __fetch($template, $cache_id = null, $arrExtra = array())
    {
        $compile_id = !empty($arrExtra['compile_id']) ? $arrExtra['compile_id'] : null;
        $this->__assignControllerTPLVars();
        
        logger::log(logger::LEVEL_INFO, 'Template ('. $template .') fetched');
        
        $return = parent::fetch($template, $cache_id, $compile_id);
        
        return $return;
    }

    /**
     * a wrapper - displays a Smarty template
     * 
     * @param string $ |object $template the resource handle of the template file  or template object
     * @param mixed $cache_id cache id to be used with this template
     * @param array $arrExtra for additional requirements
     * @result outputs the rendered template
     */
    public function __display($template, $cache_id = null, $arrExtra = array())
    {
        echo self::fetch($template, $cache_id, $compile_id);    
    }
    
    /**
     * Render a controller
     *
     * @param array $arrController containing controller & method names
     * @param array $args to be passed to the method
     */
    public function __render($arrController, $args)
    {
    
    }
}