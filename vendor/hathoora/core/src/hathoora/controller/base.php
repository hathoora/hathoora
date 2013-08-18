<?php
namespace hathoora\controller;

use hathoora\container,
    hathoora\logger\logger;

/**
 *  base controller stuff
 */
class base
{
    private $app;
    private $appNamespace;
    private $controller;
    private $action;
    private $params;
    private $controllerNameSpaceClass;
    /* store tpl variables that should be available to all templates */
    public $_tpl_vars;
    
    /**
     * constructor
     */
    public function __construct(\hathoora\router\dispatcher $dispatcher)
    {
        $this->app = $dispatcher->app;
        $this->appNamespace = $dispatcher->appNamespace;
        $this->controller = $dispatcher->controller;
        $this->action = $dispatcher->action;
        $this->params = $dispatcher->params;
        $this->controllerNameSpaceClass = $this->getControllerNameSpaceClass($this->controller);
    }
    
    /**
     * Same as isCallable, but uses current objects properties
     */
    public function isExecutable()
    {
        return $this->isCallable($this->controllerNameSpaceClass, $this->action);
    }
    
    /**
     * Check if a controller::action is callable
     * Only public functions of valid controllers are callable
     * 
     * @param string $controllerClassName the crontroller class ex: siteControllerDefault
     * @param string $action name of action
     * @return bool
     */
    public function isCallable($controller, $action)
    {
        // @todo check file before loading..
        //sqlLoadClassTest(true);
        $callable = is_callable(array(
                                    $controller, 
                                    $action));     
        //sqlLoadClassTest(false);
        
        return $callable;
    }
    
    /**
     * Same as invoke, but uses current objects properties
     */
    public function execute()
    {
        return $this->invoke($this->controllerNameSpaceClass, $this->action, $this->params);
    }

    /**
     * invoke controller action
     */
    public function invoke($controllerNameSpaceClass, $action = 'index', $params = array())
    {
        logger::log(logger::LEVEL_DEBUG, 'Calling controller ('. $controllerNameSpaceClass .'::'. $action .') with following params<br/> <pre>' . print_r($params, true) .'</pre>');
        
        $controllerObject = new $controllerNameSpaceClass();

        return call_user_func_array(array(
                                        $controllerObject,
                                        $action
                                    ), 
                                    $params);    
    }
    
    /**
     * Returns current controller namespace
     */
    public function getControllerNamespace()
    {
        return $this->controllerNameSpaceClass;
    }
    
    /**
     * Returns current controller
     */
    public function getControllerName()
    {
        return $this->controller;
    }
    
    /**
     * Returns current method
     */
    public function getControllerActionName()
    {
        return $this->action;
    }

    /**
     * Returns current controller
     */
    public function getControllerActionParams()
    {
        return $this->params;
    }    

    /**
     * Returns namespace class of controller
     *
     * @param string $controller
     */
    public function getControllerNameSpaceClass($controller)
    {
        return $this->appNamespace .'\controller\\' . $controller;
    }
}