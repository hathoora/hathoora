<?php
namespace sampleSite\controller;

use hathoora\controller\controller;

/**
 * Docs controller
 */
class docsController extends controller
{
    public $arrNav = array(
        'Getting Started' => array(
            'installation' => 'Installation',
            'structure' => 'File Structure',
            'configuration' => 'Configuration',
            'routing' => 'Routing & Pretty URLs',
            'controller' => 'Controller',
            'view' => 'View & Templates',
            'validation' => 'validation',
            'model' => 'Model',
        ),
        'Learning More' => array(
            'lifecycle' => 'Request Lifecycle',
            'database' => 'Database',
            'grid' => 'Grid',
            'cache' => 'Caching',
            'container' => 'Container',
            'services' => 'Services',
            'listeners' => 'Listeners',
            'translations' => 'Translations',
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
