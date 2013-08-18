<?php
namespace hathoora\template;

use hathoora\container,
    hathoora\logger\logger;

/**
 * PHP STUOB
 * A Simple Template Using Output Buffer
 *
 */
class templateStuob extends container implements templateInterface
{
    /**
     * The name of the directory where templates are located.
     *
     * @var string
     */
    public $template_dir = 'templates';

    /**
     * This enables template caching.
     * <ul>
     *  <li>0 = no caching</li>
     *  <li>1 = use class cache_lifetime value</li>
     * </ul>
     * @var integer
     */
    public $caching = 0;

    /**
     * The name of the directory for cache files.
     *
     * @var string
     */
    public $cache_dir = 'cache';

    /**
     * This is the number of seconds cached content will persist.
     * <ul>
     *  <li>0 = always regenerate cache</li>
     *  <li>n = seconds </li>
     * </ul>
     *
     * @var integer
     */
    public $cache_lifetime = 3600;

    /**
     * Use sub directory when $group is used
     *
     * @var bool
     */
    public $use_dir  =  TRUE;

    /**
     * All assigns are stored in this variable
     *
     * @var array
     */
    private $var;
    
    /**
     * stores blocks
     */
    private $blocks = null;
    
    /**
     * constructor
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->configure($config);
    }

    /**
     * Assign variable to be used in template
     *
     * @param string $name of the variable
     * @param mixed $value of the variable
     */
    public function __assign($name, $value)
    {
        $this->var[$name] = $value;
    }

    /**
     * Assign variable, by reference, to be used in template
     *
     * @param string $name of the variable
     * @param mixed $value of the variable
     */
    public function __assignByRef($name, &$value)
    {
        $this->var[$name] = $value;
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
        $this->var[$name] .= $value;
    }

    /**
     * get assigned value
     *
     * @param string $var
     */
    public function __getVar($var)
    {
        return $this->var[$var];
    }
    
    /**
     * get assigned vars
     */
    public function __getVars()
    {
        return $this->var;
    }    

     /**
     * Include a template
     * 
     * @param string $file if starts with / then we consider is absolute path, otherwise its relative to template_dir
     */
    public function __load($file, $vars = array())
    {
        if (substr($file, 0, 1) == '/')
            $path = $file;
        else
            $path = $this->template_dir . $file;
            
        if (file_exists($path))
        {
            $this->var['this'] =& $this;
            if (is_array($this->var))
                extract($this->var);
            
            // extract blocks
            if (is_array($this->blocks))
                extract($this->blocks);
            
            if (count($vars))
                extract($vars);
                
            // any _tpl_vars from controller\base class?
            $controller = $this->getController();
            if (is_array($controller->_tpl_vars))
                extract($controller->_tpl_vars, EXTR_OVERWRITE);

            logger::log(logger::LEVEL_INFO, 'Template ('. $file .') loaded');
            include($path);
        }
        else
            logger::log(logger::LEVEL_ERROR, 'Template ('. $file .') is not found.');
    }
    
     /**
     * Include a template
     * 
     * @param string $file if starts with / then we consider is absolute path, otherwise its relative to template_dir
     */
    public function load($file, $vars = array())
    {
        return $this->__load($file, $vars);
    }
    
    /**
     * Returns flash message and clears flash session
     */
    public function __getFlashMessage()
    {
        $request = $this->getRequest();
        $arrMessage = $request->sessionParam('httpFlash');
        $request->sessionParam('httpFlash', null, true);
        
        return $arrMessage;
    }
    
    /**
     * Returns flash message and clears flash session
     */
    public function getFlashMessage()
    {
        return $this->__getFlashMessage();
    }    
    
    /**
     * Display a template
     *
     * @param string $template the resource handle of the template file or template object
     * @param mixed $cache_id cache id to be used with this template
     * @param array $arrExtra for additional requirements
     */
    public function __display($template, $cache_id = null, $arrExtra = array())
    {
        echo $this->__fetch($template, $cache_id, $arrExtra);
    }

    /**
     * Fetch a template
     *
     * @param string $template the resource handle of the template file or template object
     * @param mixed $cache_id cache id to be used with this template
     * @param array $arrExtra for additional requirements
     */
    public function __fetch($template, $cache_id = null, $arrExtra = array())
    {
        $group = null; // for future
        if ($this->__isCached($template, $cache_id, $group))
        {
            $data = $this->read($template, $cache_id, $group);
        }
        else
        {
            $data = $this->paint($template);
            // should we cache it also?
            $this->write($template, $data, $cache_id, $group, $this->cache_lifetime);
        }
        
        return $data;
    }

    /**
     * Load template and paint
     *
     * @param string $template
     */
    public function paint($template)
    {
        if (substr($template, 0, 1) == '/' && realpath($template))
            $file = $template;
        else
            $file = $this->template_dir. DIRECTORY_SEPARATOR .$template;
        
        $this->var['this'] =& $this;
        if (is_array($this->var))
            extract($this->var);
        
        // extract blocks
        if (is_array($this->blocks))
            extract($this->blocks);
        
        // any _tpl_vars from controller\base class?
        $controller = $this->getController();
        if (is_array($controller->_tpl_vars))
            extract($controller->_tpl_vars, EXTR_OVERWRITE);
        
        $data = null;
        if ($this->templateExists($file))
        {
            ob_start();
            include_once($file);
            $data = ob_get_clean();
        }
        else
        {
            logger::log(logger::LEVEL_ERROR, 'Unable to load template: '. $file);
            throw new \exception('Unable to load template "'. $file.'"');
        }
        
        return $data;
    }

    /**
     * Stores data
     *
     * @param string $template
     * @param mixed $data which is being cached
     * @param string $id Unique ID of this data
     * @param string $group Group to store data under
     * @param int $cache_time use this cache_time, instead of class cache_lifetime
    */
    private function write($template, &$data, $id = null, $group = null, $cache_time = null)
    {
        if (!$this->caching || !$id) return false;

        // use default class cache time
        if (!$cache_time)
            $cache_time = $this->cache_lifetime;

        if (!$cache_time) return false;

        $filename = $this->getFilename($template, $id, $group);
        

        if ($fp = @fopen($filename, 'xb'))
        {
            if (flock($fp, LOCK_EX))
                fwrite($fp, $data);
            fclose($fp);

            // Set filemtime
            touch($filename, time() + $cache_time);
        }
    }

    /**
     * Reads data
     *
     * @param string $template
     * @param string $id Unique ID of this data
     * @param string $group Group to store data under
     */
    private function read($template, $id, $group = null)
    {
        $filename = $this->getFilename($template, $id, $group);
        return file_get_contents($filename);
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
        if (!$id) return false;
        
        $filename = $this->getFilename($template, $id, $group);
        if ($this->caching && file_exists($filename) && filemtime($filename) > time())
            return true;

        @unlink($filename);

        return false;
    }

    /**
     * Builds a filename/path from group, id and
     * store.
     *
     * @param string $template
     * @param string $id Unique ID of this data
     * @param string $group Group to store data under
     */
    private function getFilename($template, $id, $group = null)
    {
        // remove directory seperator from $template name
        $template = str_replace(array('/','\\'), array('_','_'), $template);
        $filename = substr($template, 0, strrpos($template, '.'));

        // use sub directories
        if ($this->use_dir && $group)
        {
            $dir = $this->cache_dir . DIRECTORY_SEPARATOR . $group;
            if (!file_exists($dir))
                mkdir($dir);
            $filename = $this->cache_dir . DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR . $filename . ($id ? '_'. $id : '') .'.cache';
        }
        else
            $filename = $this->cache_dir . DIRECTORY_SEPARATOR . $filename . ($group ? '_'.$group : '') . ($id ? '_'. $id : '') . '.cache';

        return $filename;
    }

    /**
     * Whether or nor the template exists
     *
     * @param string $template
     */
    public function templateExists($template)
    {
        if (substr($template, 0, 1) == '/' && realpath($template))
            $file = $template;
        else
            $file = $this->template_dir. DIRECTORY_SEPARATOR .$template;
            
        return file_exists($file);
    }

    /**
     * start a new block
     * @url: https://github.com/Xeoncross/PHP-Template/blob/master/Template.php
     */
    public function start()
    {
        ob_start();
    }
    
	/**
	 * End a block
	 *
	 * @param string $name name of block
     * @url: https://github.com/Xeoncross/PHP-Template/blob/master/Template.php
	 */
	public function end($name)
	{
        $name = '__block_'. $name;
		$output = ob_get_clean();
        $this->blocks[$name] = $output;
    }
    
	/**
	 * Empty default block to be extended by child templates
	 *
	 * @param string $name of block
     * @url: https://github.com/Xeoncross/PHP-Template/blob/master/Template.php
	 */
	public function block($name)
	{
        $name = '__block_'. $name;
		if (isset($this->blocks[$name]))
			echo $this->blocks[$name];
	}

	/**
	 * Extend a parent template
	 *
	 * @param string $template name of template
	 */
	public function extend($template)
	{
		ob_end_clean(); // Ignore this child class and load the parent!
        $data = $this->paint($template);
        echo $data;
	}
    
    /**
     * Generate a block - helper function comes in handy.
     *
     * @param string $block_name block name
     * @param string $template to be used
     * @param string $cache_id Unique ID of this data
     * @param string $compile_id
     * @param array $arrContent has content used in the block..
     *              function -> function to get the data
     *              function_args -> args to be passed to the function
     *              content -> this has precedence over func, and is used as is
     *              The content is available to the $template as $block_data
     *              extra -> any extra vars to be made available to the template, this is available as $block_extra
     * @param int cache_time , if null then we use the default time
     */
    public function fragment($block_name, $template, $cache_id, $compile_id, $arrContent = null, $cache_time = false)
    {
    }
    
    /**
    * Configure class
    *
    * @param array $config
    */
    public function configure(&$config)
    {
        $arrConfig = array(
            'template_dir', 'caching', 'cache_dir', 'cache_lifetime'
        );
        foreach($arrConfig as $c)
            if (isset($config[$c]))
                $this->$c = $config[$c];
    }
    
    /**
     * Render a controller
     *
     * @param array $arrController containing controller & method names
     * @param array $args to be passed to the method
     */
    public function __render($arrController, $args = array())
    {
        $action = array_pop($arrController);
        $controller = array_pop($arrController);
        $controllerObj = $this->getController();

        if (class_exists($controller))
            $nsClass = $controller;
        else
            $nsClass = $controllerObj->getControllerNameSpaceClass($controller);
        
        $response =  $controllerObj->invoke($nsClass, $action, $args); 
        $response->render();
    }
    
    /**
     * Render a controller
     *
     * @param array $arrController containing controller & method names
     * @param array $args to be passed to the method
     */
    public function render($arrController, $args = array())
    {
        return $this->__render($arrController, $args);
    }
}