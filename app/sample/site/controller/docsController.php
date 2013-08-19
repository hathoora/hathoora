<?php
namespace site\controller;

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
        $response->redirect('/docs/view/installation', 302);

        return $response;
    }

    public function view($currentNav)
    {
        $arrTplParams = array(
            'arrNav' =>& $this->arrNav,
            'currentNav' => $currentNav
        );
        $template = $this->template('docs.tpl.php', $arrTplParams);
        $response = $this->response($template);

        return $response;
    }
}
