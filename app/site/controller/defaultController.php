<?php
namespace site\controller;

use hathoora\controller\controller;

/**
 * Default controller
 */
class defaultController extends controller
{
    public function __construct()
    { }

    /**
     * Homepage action
     */
    public function index()
    {
        $template = $this->template('index.tpl.php');
        $response = $this->response($template);

        return $response;
    }
}
