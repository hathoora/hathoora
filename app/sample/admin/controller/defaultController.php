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
        $this->arrHTMLMetas['title']['value'] = 'Admin Panel';

        $arrTplParams = array();
        $template = $this->template('index.tpl.php', $arrTplParams);
        $response = $this->response($template);
        
        return $response;
    }    
}