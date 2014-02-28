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
        // redirect to documentation page
        $response = $this->response();
        $response->redirect('/sample/docs/', 302);

        return $response;
    }
}
