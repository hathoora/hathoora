<?php
namespace hathoora\template;

use hathoora\configure\config,
    hathoora\logger\logger,
    hathoora\logger\profiler;
/*
 * Template Engine
 */
class template
{
    /**
     * Stores global vars
     */
    static protected $globalVars;
    
    /**
     * template handler, the real template class which implements hathooraTemplateInterface
     */
    protected $factory;
    
    /**
     * template
     */
    protected $template;
    
    /**
     * arrExtra
     */
    public $cache_id;

    /**
     * arrExtra
     */
    public $arrExtra;
    
    /**
     * For profling
     */
    private $arrDebug = array();
    
    /**
     * Contructor
     *
     * @param string $template file name relative to app's view folder
     * @param array $vars to make available to template
     */
    public function __construct($template, $vars = array())
    {
        if (config::get('hathoora.logger.profiling'))
        {
            $this->arrDebug = array(
                'start' => microtime()
            );
        }
        
        if (!config::has('template.engine.name'))
            $engine = 'Stuob';
        else 
            $engine = config::get('hathoora.template.engine.name');
        
        $thClass =  '\hathoora\template\template'. $engine;
        
        // has custom template class
        if (config::has('template.engine.class'))
        {
            $thClass =  config::get('hathoora.template.engine.class');
        }
        
        // assign template and tpl dir
        $this->template = $template;
        $this->template_dir = HATHOORA_APP_PATH . '/resources/templates/';
        $arrTemplateConfig  = array(
            'template_dir' => $this->template_dir
        );
        
        // pass params to config
        $arrConfig = config::get('hathoora.template.' . $engine);
        if (is_array($arrConfig))
        {
            // allow overwite from $arrConfig
            $arrTemplateConfig = $arrConfig + $arrTemplateConfig;
        }

        $this->factory = new $thClass($arrTemplateConfig);
        
        // assign local and variable globals
        $vars = (array) $vars + (array) self::$globalVars;
        if (count($vars) >= 1)
        {
            foreach($vars as $k => $v)
            {
                $this->assign($k, $v);
            }
        }
    }
    
    /**
     * Assign variable to be used in template
     *
     * @param string $name of the variable
     * @param mixed $value of the variable
     */
    public function assign($name, $value, $scopeGlobal = false)
    {
        $this->factory->__assign($name, $value);
        
        if ($scopeGlobal)
            self::$globalVars[$name] = $value;
    }

    /**
    * Assign variable, by reference, to be used in template
    *
    * @param string $name of the variable
    * @param mixed $value of the variable
    * @param bool $scopeGlobal (optional) to assign a variable globally
    */
    public function assignByRef($name, &$value, $scopeGlobal = false)
    {
        $this->factory->__assignByRef($name, $value);
        
        if ($scopeGlobal)
            self::$globalVars[$name] =& $value;        
    }
    
    /**
     * Append variable to be used in template
     *
     * @param string $name of the variable
     * @param mixed $value of the variable
     */
    public function append($name, $value)
    {
        $this->factory->__append($name, $value);
    }
    
    /**
     * a wrapper - fetches a rendered template
     * 
     * @return string rendered template output
     */
    public function fetch()
    {
        $template = $this->template;
        $cache_id = $this->cache_id;
        $arrExtra = $this->arrExtra;
        if (config::get('hathoora.logger.profiling'))
        {
            $cached = $this->factory->__isCached($template, $cache_id);
            $this->arrDebug['cached'] = $cached == true ? 1 : 0;
        }
        
        $return = $this->factory->__fetch($template, $cache_id, $arrExtra);
        
        if (config::get('hathoora.logger.profiling'))
        {
            $this->arrDebug['name'] = $template;
            if ($cache_id)
                $this->arrDebug['name'] = $template . ' <em>('. $cache_id .')</em>';
            $this->arrDebug['end'] = microtime();
            profiler::profile('template', false, $this->arrDebug);
        }
        
        // log it
        logger::log(logger::LEVEL_INFO, 'Template ('. $template .') fetched.');
        
        return $return;
    }
  
    /**
     * a wrapper - displays a Smarty template
     * 
     * @param mixed $cache_id cache id to be used with this template
     * @param array $arrExtra for additional requirements
     * @result outputs the rendered template
     */
    public function display()
    {
        $return = $this->fetch();
        echo $return;
    } 
    
    /**
     * magic function for accessing template handler properties
     */
    public function __get($name)
    {
        return $this->factory->$name;
    }
    
    /**
     * magic function for setting template handler properties
     */
    public function __set($name, $value)
    {
        if (!is_object($this->factory))
            $this->factory = new \stdClass;
        
        return $this->factory->$name = $value;
    }
    
    /**
     * magic function for checking isset template handler properties
     */
    public function __isset($name)
    {
        return isset($this->factory->$name);
    }

    /**
     * magic function for unsetting template handler properties
     */
    public function __unset($name)
    {
        unset($this->factory->$name);
    }    
    
    /**
     * magic function for accessing template handler methods
     */
    public function __call($name, $args)
    {
        return $this->factory->$name($args);
    }

    /**
     * magic function for accessing static template handler methods
     */    
    public static function __callStatic($name, $args)
    {
        return self::$factory->$name($args);
    }
}