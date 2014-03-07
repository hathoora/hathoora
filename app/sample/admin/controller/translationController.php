<?php
namespace admin\controller;

use admin\grid\translation;

/**
 * Translation controller
 */
class translationController extends baseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Translation's home
     */
    public function index()
    {
        $request = $this->getRequest();

        $arrTplParams = array(
            'currentNav' => 'translation',
            'selectedSubNav' => array(
                'add' => 'Add New'
            )
        );

        $arrGridPrepare = array(
            'table' => array(
                'title' => 'Table using <code class="inline">translation::getTranslations</code> '
            )
        );
        $arrTplParams['grid'] = translation::getTranslations($arrGridPrepare, $request->getParam(), $render = true);

        $template = $this->template('translation/index.tpl.php', $arrTplParams);
        $response = $this->response($template);

        return $response;
    }   
}


