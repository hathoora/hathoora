<?php
namespace docs;

/**
 * Custom request dispatcher
 */
class customDispatcher
{
    /**
     * Custom dispatcher for route request
     *
     * This function returns array containing:
     *      - controller class name
     *      - action name
     *      - array of params
     */
    public function dispatch(\hathoora\container $container)
    {
        $arrDispatch = null;
        $request = $container->getRequest();
        $uri = $request->serverParam('REQUEST_URI');

        // default params
        $params = array('v1', 'introduction');

        if (preg_match('~^/docs/(v\d+)/([a-z0-9]+)/?~', $uri, $arrMatch))
        {
            $params = array(
                $arrMatch[1],
                $arrMatch[2]
            );
        }

        $arrDispatch = array(
                                'controller' => 'viewController',
                                'action' => 'view',
                                'params' => $params);

        return $arrDispatch;
    }
}
