<?php
namespace admin\controller;

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
        $arrTplParams = array();
        $template = $this->template('index.tpl.php', $arrTplParams);
        $response = $this->response($template);
        
        return $response;
    }    
}