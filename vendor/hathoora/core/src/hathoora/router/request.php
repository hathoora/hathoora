<?php
namespace hathoora\router;

use hathoora\configure\config;

/**
 * request class
 */
class request
{
    /**
     * A url signature thats used for determining app
     * Its basically $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
     */
    private $urlSignature = '';

    /**
     * Stores current app being used
     */
    public $app = 'site';

    /**
     * Stores apps namespace
     */
    public $appNamespace = null;

    /**
     * Stores app directory path
     */
    public $appDirectoryPath = null;

    /**
     *  Whether or not app has custom dispatcher
     */
    public $appHasDispatcher = null;

    /**
     * Stores current URI
     */
    public $uri;

    /**
     * Stores base url (including app) without http(s): prefix
     */
    public $baseURS;

    /**
     * stores URI by removing MVC domain base
     */
    public $routeURI;

    /**
     * Stores route.php config
     */
    private $routeConfig;

    /**
     * Constructor function which loads route.php
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * initialize function
     */
    private function init()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $org_req_url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        else
            $org_req_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $query_url = parse_url($org_req_url, PHP_URL_QUERY);
        // very important to remove trailing slashes otherwise dispatcher would get confused
        $req_url = str_replace($query_url, '', $org_req_url);
        $this->uri = $this->removeTrailingSlash(parse_url($req_url, PHP_URL_PATH));
        $this->routeURI = $this->removeTrailingSlash($this->uri);
        $this->baseURS = '//' . $this->removeTrailingSlash($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

        $this->urlSignature = $_SERVER['HTTP_HOST'] . parse_url($org_req_url, PHP_URL_PATH);

        $this->routeConfig = new config(array(
            HATHOORA_ROOTPATH . 'boot/config/app.yml',
            HATHOORA_ROOTPATH . 'boot/config/app_'. HATHOORA_ENV .'.yml'));
    }

    /**
     * This function reads configuration (from config/route.php) to fignure out mvc domain.
     * First match wins.
     */
    public function getApp()
    {
        static $appMatched;

        if ($appMatched && $this->app)
            return $this->app;

        $arrApps = $this->routeConfig->get('app');
        $appMatched = false;

        if (is_array($arrApps))
        {
            foreach($arrApps as $app => $regex)
            {
                $appNamespace = '\app\\' . $app;
                $appDirectoryPath = HATHOORA_ROOTPATH . 'app/' . $app . '/';
                $appHasDispatcher = null;
                if (is_array($regex))
                {
                    $arrApp = $regex;
                    $regex = !empty($arrApp['pattern']) ? $arrApp['pattern'] : null;
                    $directory = !empty($arrApp['directory']) ? $arrApp['directory'] : null;
                    if (!empty($arrApp['default']))
                        $regex = '([a-z0-9\-]+\.)*[a-z0-9]+\.[a-z]+';

                    if (!empty($arrApp['dispatcher']) && is_array($arrApp['dispatcher']))
                        $appHasDispatcher = $arrApp['dispatcher'];

                    // add app to namespace and rememver 'autoloader' was already included in front end controller
                    if ($directory)
                    {
                        $appNamespace = '\\'. $app;
                        $path = HATHOORA_ROOTPATH . 'app/' . $directory .'/';
                        $appDirectoryPath = $path . $app . '/';

                        \hathoora\autoload::register($app, $path);
                    }
                }


                if ($regex && empty($appMatched) && preg_match('%'. $regex .'%i', $this->urlSignature, $arrMatch))
                {
                    $appMatched = true;
                    $this->app = $app;
                    $this->appNamespace = $appNamespace;
                    $this->appDirectoryPath = $appDirectoryPath;
                    $this->appHasDispatcher = $appHasDispatcher;

                    // seperate domain from $_SERVER['HTTP_HOST']
                    $this->routeURI = str_replace($arrMatch[0], '', $this->urlSignature);
                    $this->baseURS = str_replace($this->routeURI, '', $this->baseURS);
                }
            }
        }

        return $this->app;
    }

    /**
     * Return app config as defined in app.yml file
     */
    public function getAppConfig($app = null)
    {
        $arrAppInfo = null;

        if (!$app)
            $app = $this->app;

        $arrApps = $this->routeConfig->get('app');

        if (isset($arrApps[$app]))
            $arrAppInfo = $arrApps[$app];

        return $arrAppInfo;
    }

    /**
     * Returns directory path for a given app
     */
    public function getAppDirectory($app = null)
    {
        if ($app)
        {
            $arrApps = $this->routeConfig->get('app');

            if (isset($arrApps[$app]) && ($arrApp = $arrApps[$app]))
            {
                if (isset($arrApp['directory']) && ($directory = $arrApp['directory']))
                    $path = HATHOORA_ROOTPATH . 'app/' . $directory .'/' . $app . '/';
                else
                    $path = HATHOORA_ROOTPATH . 'app/' . $app . '/';
            }
        }
        else
            $path = $this->appDirectoryPath;

        return $path;
    }

    /**
     * Dispatch the request
     *
     * @return \hathoora\router\dispatcher::dispatch()
     */
    public function dispatch()
    {
        $dispatcher = new dispatcher($this);
        return $dispatcher->dispatch();
    }

    /**
     * Removes trailing slashes
     */
    public function removeTrailingSlash($str)
    {
        return rtrim($str, '/');
    }
}