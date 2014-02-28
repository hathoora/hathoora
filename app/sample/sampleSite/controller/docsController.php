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
            'configuration' => 'Configuration',
            'structure' => 'File Structure',
            'routing' => 'Routing & URLs',
            'controller' => 'Controller',
            'model' => 'Model',
        ),
        'Learning More' => array(
            'database' => 'Database'
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
        $currentNav = strtolower($currentNav);
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
