<?php
namespace admin\controller;

/**
 * Default controller
 */
class defaultController extends baseController
{
    public function __construct()
    {
        parent::__construct();
    }
    
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