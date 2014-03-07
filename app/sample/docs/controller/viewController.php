<?php
namespace docs\controller;

use hathoora\controller\controller;

/**
 * Docs controller
 */
class viewController extends controller
{
    public $arrNav = array(
        'Overview' => array(
            'introduction' => 'Introduction',
            'concetps' => 'Concepts',
            'organization' => 'Organization'
        ),
        'Getting Started' => array(
            'installation' => 'Installation',
            'configuration' => 'Configuration',
            'routing' => 'Routing',
            'controller' => 'Controller',
            'view' => 'Templates',
            'validation' => 'Validation',
            'model' => 'Model',
            'debugging' => 'Debugging'
        ),
        'Learning More' => array(
            'container' => 'Container',
            'services' => 'Services',
            'database' => 'Database',
            'grid' => 'Grid',
            'cache' => 'Caching',
            'listeners' => 'Listeners',
            'translations' => 'Translations',
            'cli' => 'Command Line'
        ),
        'Samples' => array(
            'admin' => 'Admin Panel',
            'lists' => 'Lists',
            'chat' => 'Chat'
        )
    );

    /**
     * docs main
     */
    public function index()
    {
        // redirect to installation page
        $response = $this->response();
        $response->redirect('/docs/v1/introduction', 302);

        return $response;
    }

    /**
     * View docs
     *
     * @param $version
     * @param $currentNav
     * @return \hathoora\http\response
     */
    public function view($version, $currentNav)
    {
        if (true && preg_match('/^v\d+$/', $version))
        {
            $arrTplParams = array(
                'arrNav' =>& $this->arrNav,
                'currentNav' => $currentNav,
                'version' => $version
            );
            $template = $this->template('docs.tpl.php', $arrTplParams);
            $response = $this->response($template);
        }
        else
        {
            $response = $this->response();
            $response->redirect('/docs/v1/installation', 302);
        }

        return $response;
    }
}
