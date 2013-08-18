<?php
namespace admin\controller;

use hathoora\controller\controller;

/**
 * Translation controller
 */
class translationController extends controller
{
    public function __construct()
    { }
    
    /**
     * Homepage action
     */
    public function index()
    {
        $arrTplParams = array();
        $template = $this->template('translation/index.tpl.php', $arrTplParams);
        $response = $this->response($template);
        
        return $response;
    }   
}