<?php
namespace sampleSite\controller;

use hathoora\controller\controller;

/**
 * Docs controller
 */
class docsController extends controller
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
        )
    );

    public function __construct()
    { }

    /**
     * docs main
     */
    public function index()
    {
        // redirect to installation page
        $response = $this->response();
        $response->redirect('/sample/docs/view/installation', 302);

        return $response;
    }

    public function view($currentNav)
    {
        if (true)
        {
            $arrTplParams = array(
                'arrNav' =>& $this->arrNav,
                'currentNav' => $currentNav
            );
            $template = $this->template('docs.tpl.php', $arrTplParams);
            $response = $this->response($template);
        }
        else
        {
            $response = $this->response();
            $response->redirect('/sample/docs/view/installation', 302);
        }

        return $response;
    }
}
