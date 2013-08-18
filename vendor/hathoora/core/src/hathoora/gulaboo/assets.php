<?php
namespace hathoora\gulaboo;

use hathoora\container;

/**
 * Assets manager
 */
class assets extends container
{
    /**
     * holds container
     */
    private $container;

    public function __construct(/*$container*/)
    {
        //$this->container =& $container;
    }
    
    /**
     * This function retrivers an app asset
     */
    public function getAppAsset($path, $app = null)
    {
        static $arrAppPathHash;

        $appDirectory = null;
	
        if (empty($app))
            $app = HATHOORA_APP;

        if (!isset($arrAppPathHash[$app]))
        {
            $arrApp = $this->getRouteRequest()->getAppConfig($app);
            if (isset($arrApp['directory']))
                $appDirectory = $arrApp['directory'];

            $arrAppPathHash[$app] = $appDirectory . '::'. $app . '/';
        }
        
        $appPathHash = $arrAppPathHash[$app];
        
        $url = $this->getConfig('assets.urls.http');
        $url .= '/_assets/_app/' . $appPathHash;
        $url .=  $path . '?' . $this->getConfig('assets.version');
        
        return $url;    
    }
    
    public function getAsset($path, $versioning = true)
    {
        $url = $this->getConfig('assets.urls.http');
        //$url = null; //'http://media01.mine.pk';
        $url .=  $path . '?' . $this->getConfig('assets.version');
        //$url .= time();
        
        return $url;
    }
}
